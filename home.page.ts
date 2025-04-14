import { Component, ViewChild } from '@angular/core';
import { InfiniteScrollCustomEvent, IonInfiniteScroll, IonRefresher } from '@ionic/angular';
import { concat, defer, forkJoin, from, Observable, of, Subject, timer } from 'rxjs';
import {
  delay,
  delayWhen,
  distinctUntilChanged,
  distinctUntilKeyChanged,
  retryWhen,
  switchMap,
  take,
  tap,
} from 'rxjs/operators';
import { environment } from 'src/environments/environment';
import { Observation, Professional } from '../models/observation';
import { ObservationPOST, ProfessionalPOST } from '../models/observationPOST';
import { SelectOptions } from '../models/selectOptions';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { CachingService } from '../services/caching.service';
import { StorageService } from '../services/storage.service';
import { User } from '../models/user';
import { TranslateService } from '@ngx-translate/core';
import { LanguageService } from '../services/language.service';
import { Network } from '@capacitor/network';
import { Capacitor } from '@capacitor/core';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
  standalone: false,
})
export class HomePage {
  observations: Observation[] = [];
  cacheObservations: Observation[] = [];

  shifts: SelectOptions[] = [];
  departments: SelectOptions[] = [];
  services: SelectOptions[] = [];
  categories: SelectOptions[] = [];

  user: User;

  hrefNext: string;
  count: number = 0;
  lang: string = this.language.currentLangValue();
  networkStatus = new Subject<boolean>();

  constructor(
    private api: ApiService,
    private authService: AuthService,
    private storageService: StorageService,
    private cachingService: CachingService,
    private translate: TranslateService,
    private language: LanguageService
  ) {
    Network.addListener('networkStatusChange', async (status) => {
      if (Capacitor.isNativePlatform()) {
        this.networkStatus.next(status.connected);
      }
    });

    this.networkStatus.pipe(distinctUntilChanged()).subscribe((connected) => {
      if (connected) {
        console.log('Network status:', connected);
        this.sync(nothimngResfresh);
      }
    });
    this.authService.getToken().subscribe(async (logged) => {
      if (logged == null) {
        return;
      }
      if (logged != null) {
        console.log('   [ CONSTRUCTOR] this.authService.getToken()');
        const storageToken = await this.storageService.get(this.storageService.TOKEN);
        if (logged || storageToken) {
          this.handleDepartments(true);
          this.handleServices(true);
          this.handleShifts(true);
          this.handleCategories(true);
          this.handleUser(true);
          this.handleHospital(true);
          this.getActions(true);
          this.getIndications(true);
          //gestio offline
          this.handleObservations(true).catch((err) => console.log('error handle Observations', err));
        }
      }
    });

    this.api.getRefresh().subscribe((refresh: boolean) => {
      //quan fas pulll to rtefresh
      if (refresh) {
        console.log(' [ CONSTRUCTOR] Api refresh');

        console.log(refresh);
        this.handleObservations(true).catch((err) => console.log('error handle Observations', err));
        this.api.setRefresh(false);
      }
    });
    this.language.currentLang().subscribe((lang) => {
      if (!!lang) {
        this.lang = lang;
      }
    });
  }

  async handleCacheObservations() {
    this.cacheObservations = await this.cachingService.getCachedRequest(this.storageService.OBSERVATIONS_CACHE);
    console.log('cache observations', this.cacheObservations);
    if (!this.cacheObservations) return;
    if (this.cacheObservations) {
      this.cacheObservations = this.cacheObservations.map((element) => {
        if (element.sync === false) {
          element.name = this.getObservationId();
          this.observations.push(element);
          return element;
        } else {
          console.log('Observation already synced', element.id);
          return undefined;
        }
      });
      //Update cache
      await this.cachingService.saveToCache(
        this.storageService.OBSERVATIONS_CACHE,
        this.cacheObservations.filter((x) => x !== undefined)
      );
    }
  }

  get cachedObs() {
    if (this.cacheObservations) {
      return this.cacheObservations.filter((co) => co.sync === false).length;
    } else {
      return 0;
    }
  }

  async handleObservations(evt?) {
    this.observations = [];
    this.api
      .fetchAllAtOnce(!!evt ? true : false)
      .pipe(this.api.uify())
      .subscribe((response: any) => {
        this.storageService.set(this.storageService.OBSERVATIONS, response);
        for (let res of response) {
          if (res.data === undefined) return;
          const data = res.data.filter((x: any) => x.removed == false);

          console.log(
            'handleObservations: ',
            data.map((x: any) => x.id)
          );

          if (data) {
            data.forEach((element: any) => {
              this.convertFromServer(element);
            });
          }
        }
        this.observations = this.ensureUniqueObjects(this.observations);
        this.handleCacheObservations().catch((err) => console.log('error handle cache Observations', err));
        this.count = 0;
      });
  }

  ensureUniqueObjects(jsonArray: any[]) {
    const seenObjects = new Set<string>();
    let uniqueArray = [];

    for (let obj of jsonArray) {
      const jsonString = JSON.stringify(obj, Object.keys(obj).sort());
      if (!seenObjects.has(jsonString)) {
        seenObjects.add(jsonString);
        uniqueArray.push(obj);
      }
    }

    return uniqueArray;
  }

  getObservationId() {
    console.log(this.observations);
    return (parseInt(this.observations[this.observations.length - 1]?.name || '0') + 1).toString().padStart(8, '0');
  }

  async sync(evt?) {
    console.log('sync', evt);

    if (!!this.cacheObservations) {
      console.log('Observacions per sincronitzar', this.cacheObservations);

      for (let i = 0; i < this.cacheObservations.length; i++) {
        const o = this.cacheObservations[i];
        if (o.sync === false) {
          let observationPost: ObservationPOST = {
            service: o.service.id,
            department: o.department.id,
            shift: o.turn.id,
            hospital: o.department.hospital,
            startDate: (o.start_datetime / 1000).toFixed(0),
            endDate: (o.end_datetime / 1000).toFixed(0),
            professionals: o.profesionals,
          };

          console.log('Sincronitzant ' + o.id);
          const response = await this.api.sendObservation(observationPost, evt).toPromise();

          if (!!response) {
            const obs = response;
            o.sync = true;
            this.cachingService.cacheRequest(this.storageService.OBSERVATIONS_CACHE, this.cacheObservations);
            console.log('SendObservation', obs);
            let synced_date = Date.now();
            this.storageService.set(this.storageService.SYNC_DATE, synced_date);
            this.api.setSyncDate(synced_date);
          } else {
            console.log('Error sync observation');
          }
        }
      }
      this.api.setRefresh(true);
    } else {
      console.log('No hi ha observacions per sincronitzar');
    }
    if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
    this.handleObservations().catch((err) => console.log('error handle Observations', err));
  }

  convertFromServer(observation: any) {
    if (observation) {
      console.log('observation original', observation);
      console.log('observation', {
        id: observation.id,
        name: observation.name,
        //seconds time stamp epoch
        start_datetime: observation.startDate * 1000,
        end_datetime: observation.endDate * 1000,
        service: this.services.find((s) => s.id === observation.service),
        department: this.departments.find((s) => s.id === observation.department),
        turn: this.shifts.find((s) => s.id === observation.shift),
        profesionals: observation.professionals,
        duration: observation.duration,
        sync: true,
        counter: this.countOpportunities(observation.professionals),
      });

      this.observations.push({
        id: observation.id,
        name: observation.name,
        //seconds time stamp epoch
        start_datetime: observation.startDate * 1000,
        end_datetime: observation.endDate * 1000,
        service: this.services.find((s) => s.id === observation.service),
        department: this.departments.find((s) => s.id === observation.department),
        turn: this.shifts.find((s) => s.id === observation.shift),
        profesionals: observation.professionals,
        duration: observation.duration,
        sync: true,
        counter: this.countOpportunities(observation.professionals),
      });
    }
  }

  countOpportunities(professionals: ProfessionalPOST[]): number {
    let counter = 0;
    professionals.forEach((p) => {
      p.opportunities.forEach((o) => {
        counter++;
      });
    });
    return counter;
  }

  handleShifts(evt?) {
    this.api.shifts(!!evt ? true : false).subscribe(
      (nxt) => {
        console.log('handleShifts: ', nxt);
        let nxt_data = nxt;

        const data = nxt_data.filter((x) => x.removed == false);
        this.api.setSelect(3, data), (this.shifts = data);
      },
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  handleServices(evt?) {
    this.api.services(!!evt ? true : false).subscribe(
      (nxt) => {
        let nxt_data = nxt;
        const data = nxt_data.filter((x) => x.removed == false);
        this.api.setSelect(1, data), (this.services = data);
      },
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  handleDepartments(evt?) {
    this.api.departments(!!evt ? true : false).subscribe(
      (nxt) => {
        let nxt_data = nxt;

        const data = nxt_data.filter((x) => x.removed == false);
        this.api.setSelect(2, data), (this.departments = data);
      },
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  handleCategories(evt?) {
    this.api.categories(!!evt ? true : false).subscribe(
      (nxt) => {
        let nxt_data = nxt;
        const data = nxt_data.filter((x) => x.removed == false);
        this.api.setSelect(4, data);
        this.categories = data;
      },
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  handleUser(evt?) {
    const extractVal = (udat: any, field: string) => {
      const ari = udat[field];
      let retval = '';
      if (Array.isArray(ari) && ari.length) {
        const obj = ari[0];
        if (!!obj) retval = obj.value;
      }
      return retval;
    };

    this.api.user(!!evt ? true : false).subscribe(
      (nxt) => {
        this.user = {
          id: extractVal(nxt, 'uid'),
          self: '',
          userName: extractVal(nxt, 'name'),
          firstName: extractVal(nxt, 'name'),
          lastName: '',
          mail: extractVal(nxt, 'mail'),
          language: extractVal(nxt, 'langcode'),
        };

        this.api.setUser(this.user);
      },
      (err) => {
        console.log(this.constructor.name, err);
        this.api.refreshToken(err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  handleHospital(evt?) {
    this.api.hospitals(true).subscribe(
      (nxt) => {
        const hospital = nxt;
        this.api.setHospital(hospital);
      },
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  getActions(evt?) {
    this.api.actions(!!evt ? true : false).subscribe(
      (nxt) => {},
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }

  getIndications(evt?) {
    this.api.indications(!!evt ? true : false).subscribe(
      (nxt) => {},
      (err) => {
        console.log(this.constructor.name, err);
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      },
      () => {
        if (evt?.target) ((<any>evt.target) as IonRefresher).complete();
      }
    );
  }
}

let nothimngResfresh = {
  target: {
    complete: () => {
      console.log('complete faked refreah');
    },
  },
};
