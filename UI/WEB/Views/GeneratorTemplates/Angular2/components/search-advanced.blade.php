import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';

import { {{ $entitySin = $gen->entityName() }} } from './../../models/{{ camel_case($entitySin) }}';

{{ '@' }}Component({
  selector: '{{ str_replace(['.ts', '.'], ['', '-'], $gen->componentFile('search-advanced', false)) }}',
  templateUrl: './{{ $gen->componentFile('search-advanced-html', false) }}',
  styleUrls: ['./{{ $gen->componentFile('search-advanced-css', false) }}']
})
export class {{ $gen->componentClass('search-advanced', $plural = false) }} implements OnInit {
  
  @Input()
  public formModel: any;

  @Input()
  public formData: any;

  @Input()
  public form: FormGroup;

  @Input()
  public errors: Object = {};

  @Output()
  public search = new EventEmitter<{}>();

  @Output()
  public filterBtnClick = new EventEmitter<null>();

  @Input()
  public debug: boolean = false;
  
  public constructor(private fb: FormBuilder) { }

  public ngOnInit() {
  	this.form.addControl('page', new FormControl(1));
  }
}
