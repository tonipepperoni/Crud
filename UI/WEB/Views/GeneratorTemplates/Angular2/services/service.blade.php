import { Injectable } from '@angular/core';
import { Http, URLSearchParams } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/catch';
import { TranslateService } from '@ngx-translate/core';

import { Service } from './../../core/services/abstract.service';
import { LocalStorageService } from './../../core/services/local-storage.service';
import { {{ ($entitySin = $gen->entityName()).'Pagination' }} } from './../models/{{ camel_case($entitySin)."Pagination" }}';
import { {{ $entitySin }} } from './../models/{{ camel_case($entitySin) }}';
import { AppMessage } from './../../core/models/appMessage';

/**
 * {{ $entitySin }}Service Class.
 *
 * @author [name] <[<email address>]>
 */
@Injectable()
export class {{ $entitySin }}Service extends Service {
	/**
   * API endpoint.
   * @type string
   */
	protected API_ENDPOINT: string = 'v1/{{ str_slug($gen->tableName, $separator = "-") }}';

  /**
   * The key to access language strings.
   * @type string
   */
  public langKey: string = '{{ $gen->entityNameSnakeCase() }}';

  /**
   * Langage key to access form fields translations.
   * @type string
   */
  public fieldsLangKey: string = this.langKey + '.fields.{{ $gen->tableName }}.';

  /**
   * The required columns to include on each API call.
   * @type Array<string>
   */
  protected required_columns = [
    '{{ $gen->tableName }}.id',
    {!! $gen->hasSoftDeleteColumn ? "'".$gen->tableName.".deleted_at'," : null !!}
  ];

  /**
   * {{ $entitySin }}Service contructor.
   */
	public constructor(
    private http: Http,
    private localStorageService: LocalStorageService,
    private translateService: TranslateService,
  ) { super(); }

  /**
   * Get the {{ $gen->entityName() }} form model.
   */
  public getFormModel(): Observable<Object> {
    this.setAuthorizationHeader();

    return this.http
      .get(this.apiEndpoint('form-model'), { headers: this.headers })
      .map(res => { return res.json() })
      .catch(this.handleError);
  }

  /**
   * Get the {{ $gen->entityName() }} form data.
   */
  public getFormData(): Observable<Object> {
    this.setAuthorizationHeader();

    return this.http
      .get(this.apiEndpoint('form-data'), { headers: this.headers })
      .map(res => { return res.json() })
      .catch(this.handleError);
  }

  /**
   * Load {{ $gen->entityName(true) }}.
   */
  public load(data: Object = {}): Observable<{{ $entitySin.'Pagination' }}> {
    this.setAuthorizationHeader();
    let searchParams = this.parseGetParams(data);

    return this.http
      .get(this.apiEndpoint(), { headers: this.headers, search: searchParams })
      .map(res => { return { data: res.json().data, pagination: res.json().meta.pagination } })
      .catch(this.handleError);
  }

  /**
   * Create {{ $gen->entityName() }}.
   */
  public create(data: Object): Observable<{{ $entitySin }}> {
    this.setAuthorizationHeader();

    return this.http
      .post(this.apiEndpoint('create'), data, { headers: this.headers })
      .map(res => { return res.json().data })
      .catch(this.handleError);
  }

  /**
   * Get {{ $gen->entityName() }} by id.
   */
  public get(id): Observable<{{ $entitySin }}> {
    this.setAuthorizationHeader();

    let urlParams: URLSearchParams = new URLSearchParams;
    urlParams.set('include', '{{ $fields->filter(function ($field) { return !empty($field->namespace); })->transform(function($field) use ($gen) { return $gen->relationNameFromField($field); })->implode(',') }}');
    return this.http
      .get(this.apiEndpoint(id), { headers: this.headers, search: urlParams })
      .map(res => { return res.json().data })
      .catch(this.handleError);
  }

  /**
   * Update {{ $gen->entityName() }}.
   */
  public update(data: {{ $entitySin }}): Observable<{{ $entitySin }}> {
    this.setAuthorizationHeader();

    return this.http
      .put(this.apiEndpoint(data.id), data, { headers: this.headers })
      .map(res => { return res.json().data })
      .catch(this.handleError);
  }

  /**
   * Delete {{ $gen->entityName() }} by id.
   */
  public delete(id: string): Observable<any> {
    this.setAuthorizationHeader();
    
    return this.http
      .delete(this.apiEndpoint(id), { headers: this.headers })
      .map(res => { return res.json().data })
      .catch(this.handleError);
  }

  /**
   * Get translated message.
   */
  public getMessage(type: string = 'create'): AppMessage {
    let msg: string;

    this.translateService
      .get(this.langKey + '.msg.' + type + '_succcess')
      .subscribe(trans => msg = trans);

    let appMessage: AppMessage = {
      message: msg,
      date: new Date(),
      errors: {},
      type: 'success',
      status_code: 200
    };

    return appMessage;
  }
}