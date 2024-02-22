import { BaseClass } from "@/Modules/core/domain/BaseClass.js";

export class Transaction extends BaseClass {
  constructor(params) {
    super();
    this.id = params.id;
    this.item_id = params.item_id;
    this.barcode = params.barcode;
    this.transac_type = params.transac_type;
    this.quantity = params.quantity;
    this.unit_price = params.unit_price;
    this.unit = params.unit;
    this.total_cost = params.total_cost;
    this.personnel_id = params.personnel_id;
    this.supplier_id = params.supplier_id;
    this.expiration = params.expiration;
    this.remarks = params.remarks;
  }

  static toObject(transaction) {
    return {
      id: transaction.id,
      item_id: transaction.item_id,
      barcode: transaction.barcode,
      transac_type: transaction.transac_type,
      quantity: transaction.quantity,
      unit_price: transaction.unit_price,
      unit: transaction.unit,
      total_cost: transaction.total_cost,
      personnel_id: transaction.personnel_id,
      supplier_id: transaction.supplier_id,
      expiration: transaction.expiration,
      remarks: transaction.remarks,
    };
  }

  static getColumns() {
    return [
        {
            title: 'ID',
            key: 'id',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Item ID',
            key: 'item_id',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Barcode',
            key: 'barcode',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Transaction Type',
            key: 'transac_type',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Quantity',
            key: 'quantity',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Unit Price',
            key: 'unit_price',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Unit',
            key: 'unit',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Total Cost',
            key: 'total_cost',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Personnel ID',
            key: 'personnel_id',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Supplier ID',
            key: 'supplier_id',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Expiration',
            key: 'expiration',
            align: 'center',
            sortable: true,
            visible: true,
        },
        {
            title: 'Remarks',
            key: 'remarks',
            align: 'center',
            sortable: true,
            visible: true,
        },
    ];
  }
}
