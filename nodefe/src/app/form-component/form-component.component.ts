import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormArray, FormControl } from '@angular/forms';
import { DataServiceService } from '../services/data-service.service';
import { RequestParams } from '../shared/requestParams';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-form-component',
  templateUrl: './form-component.component.html',
  styleUrls: ['./form-component.component.scss']
})
export class FormComponentComponent implements OnInit {

  // Components values.

  // Declared static due call of 'formatSliderLabel' method happens before of it's initialization.
  private static HDDVSliderValues: string[] = [
    '0', '250GB', '500GB', '1TB', '2TB', '3TB', '4TB', '8TB', '12TB', '24TB', '48TB', '72TB',
  ];
  ramCheckboxes: string[] = [
    '2GB', '4GB', '8GB', '12GB', '16GB', '24GB', '32GB', '48GB', '64GB', '96GB',
  ];
  HddOptions: string[] = [
    'SAS', 'SATA', 'SSD',
  ];
  sliderOptMax: number = FormComponentComponent.HDDVSliderValues.length - 1;
  locationOptions: string[];

  form: FormGroup;

  // Other properties.

  remoteData;

  constructor(private formBuilder: FormBuilder, private dataService: DataServiceService,
              private snackBar: MatSnackBar) {
    this.form = this.formBuilder.group({
      ramValues: new FormArray([]),
      sliderStorage: new FormControl(),
      hddValues: new FormControl(),
      locationValues: new FormControl(),
    });

    this.addRamCheckboxes();
  }

  ngOnInit(): void {
    this.getData();
  }

  getData(params?: RequestParams): void {
    this.dataService.getData(params).subscribe(responseData => {
      this.remoteData = responseData.data;
      this.getLocationsData();
    });
  }

  // Submit form data.
  submit() {
    const selectedOrderIds = this.form.value.ramValues
      .map((v, i) => (v ? this.ramCheckboxes[i] : null))
      .filter(v => v !== null);

    if (selectedOrderIds.length === 0 &&
      (this.form.value.sliderStorage === null || this.form.value.sliderStorage === 0)  &&
      this.form.value.hddValues === null &&
      this.form.value.locationValues === null) {
      this.openSnackBar();
      return;
    }

    const params: RequestParams = {
      Storage: (this.form.value.sliderStorage === null || this.form.value.sliderStorage === 0) ?
        null : FormComponentComponent.HDDVSliderValues[this.form.value.sliderStorage],
      Hdd: this.form.value.hddValues,
      Location: this.form.value.locationValues,
      Ram: selectedOrderIds,
    };

    this.getData(params);
  }

  resetData() {
    this.form.reset();
    this.getData();
  }

  openSnackBar() {
    this.snackBar.open('You should set at least a filter', 'Close');
  }

  // Getter of form items.
  get ramValues() { return this.form.get('ramValues') as FormArray; }
  get sliderStorage() { return this.form.get('sliderStorage'); }
  get hddValues() { return this.form.get('hddValues'); }
  get locationValues() { return this.form.get('locationValues'); }

  // Callable to format slider thumb.
  formatSliderLabel(value: number) {
    return FormComponentComponent.HDDVSliderValues[value];
  }

  // Add checkboxes to form controls.
  private addRamCheckboxes() {
    this.ramCheckboxes.forEach((o, i) => {
      const control = new FormControl();
      (this.form.controls.ramValues as FormArray).push(control);
    });
  }

  // Get locations data from Remote Backend.
  private getLocationsData() {
    this.locationOptions = this.remoteData.map((item) => {
      return item.Location;
    }).filter((v, i, a) => a.indexOf(v) === i);
  }

}

