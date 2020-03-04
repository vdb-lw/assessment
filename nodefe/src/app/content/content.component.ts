import {Component, OnInit, ViewChild} from '@angular/core';
import { DataServiceService } from '../services/data-service.service';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { MatCheckboxChange } from '@angular/material/checkbox';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-content',
  templateUrl: './content.component.html',
  styleUrls: ['./content.component.scss']
})
export class ContentComponent implements OnInit {

  elements: ListElement[];
  selectedItems: number[];
  comparedItems: boolean;
  contentDataSource;
  tableColumns: string[] = ['Model', 'Storage', 'Ram', 'Location', 'Price', 'Compare'];
  @ViewChild(MatSort) sort: MatSort;

  constructor(private dataService: DataServiceService, private snackBar: MatSnackBar) {
    this.elements = [];
    this.selectedItems = [];
    this.comparedItems = false;
  }

  getData(): void {
    this.dataService.remoteData$.subscribe(responseData => {
      if (responseData.data) {
        this.elements = [];
        responseData.data.forEach((item) => {
          const e: ListElement = {
            Id: item.Id,
            Model: item.Model,
            Hdd: item.Hdd,
            Ram: item.Ram,
            Location: item.Location,
            Price: item.Price,
            checked: false
          };
          (this.elements).push(e);
        });

        this.contentDataSource = new MatTableDataSource(this.elements);
        this.contentDataSource.sort = this.sort;
      }
    });
  }

  ngOnInit(): void {
    this.getData();
  }

  compareEvent(event: Event) {

    if (this.selectedItems.length < 2) {
      this.openSnackBar();
      return;
    }

    const filteredElements = this.elements.filter((item) => {
      return this.selectedItems.find(element => element === item.Id);
    });

    this.contentDataSource = new MatTableDataSource(filteredElements);
    this.contentDataSource.sort = this.sort;
    this.comparedItems = true;
  }

  resetEvent() {
    this.comparedItems = false;
    this.selectedItems = [];
    this.elements.forEach((item) => item.checked = false);
    this.contentDataSource = new MatTableDataSource(this.elements);
    this.contentDataSource.sort = this.sort;
  }

  OnChangeCheckbox(event: MatCheckboxChange, id: number) {
    if (event.checked === true) {
      this.selectedItems.push(id);
      return;
    }
    this.selectedItems = this.selectedItems.filter((value)  => value !== id);
  }

  openSnackBar() {
    this.snackBar.open('You should select at least two products to compare it', 'Close');
  }

}

export interface ListElement {
  Id: number;
  Model: string;
  Hdd: number;
  Ram: number;
  Location: string;
  Price: string;
  checked: boolean;
}
