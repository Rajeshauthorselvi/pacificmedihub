$(document).ready(function() {
/*Product Sec*/
	  Check_SelectAllButton('.product-read-all','.product-read');
      $('.product-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-read').prop('checked',false);
        }
      });
      Check_SelectAllButton('.product-create-all','.product-create');
      $('.product-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-create').prop('checked',false);
        }
      });
      Check_SelectAllButton('.product-update-all','.product-update');
      $('.product-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-update').prop('checked',false);
        }
      });
      Check_SelectAllButton('.product-delete-all','.product-delete');
      $('.product-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-delete').prop('checked',false);
        }
      });
/*Product Sec*/

/*Purchase*/
	Check_SelectAllButton('.purchase-read-all','.purchase-read');
      $('.purchase-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-read').prop('checked',false);
        }
      });
    Check_SelectAllButton('.purchase-create-all','.purchase-create');
      $('.purchase-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-create').prop('checked',false);
        }
      });
    Check_SelectAllButton('.purchase-update-all','.purchase-update');
      $('.purchase-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-update').prop('checked',false);
        }
      });
    Check_SelectAllButton('.purchase-delete-all','.purchase-delete');
      $('.purchase-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-delete').prop('checked',false);
        }
      });
/*Purchase*/

/*Stock*/

	Check_SelectAllButton('.stock-read-all','.stock-read');
      $('.stock-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-read').prop('checked',false);
        }
      });
    Check_SelectAllButton('.stock-create-all','.stock-create');
      $('.stock-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-create').prop('checked',false);
        }
      });
    Check_SelectAllButton('.stock-update-all','.stock-update');
      $('.stock-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-update').prop('checked',false);
        }
      });
    Check_SelectAllButton('.stock-delete-all','.stock-delete');
      $('.stock-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-delete').prop('checked',false);
        }
      });
/*Stock*/

/*RFQ*/
		Check_SelectAllButton('.rfq-read-all','.rfq-read');
      $('.rfq-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-read').prop('checked',false);
        }
      });
      Check_SelectAllButton('.rfq-create-all','.rfq-create');
      $('.rfq-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-create').prop('checked',false);
        }
      });
      Check_SelectAllButton('.rfq-update-all','.rfq-update');
      $('.rfq-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-update').prop('checked',false);
        }
      });
      Check_SelectAllButton('.rfq-delete-all','.rfq-delete');
      $('.rfq-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-delete').prop('checked',false);
        }
      });
/*RFQ*/
/*Orders*/
	Check_SelectAllButton('.orders-read-all','.orders-read');
      $('.orders-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-read').prop('checked',false);
        }
      });
    Check_SelectAllButton('.orders-create-all','.orders-create');
      $('.orders-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-create').prop('checked',false);
        }
      });
    Check_SelectAllButton('.orders-update-all','.orders-update');
      $('.orders-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-update').prop('checked',false);
        }
      });
    Check_SelectAllButton('.orders-delete-all','.orders-delete');
      $('.orders-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-delete').prop('checked',false);
        }
      });
/*Orders*/

/*Customer*/
	Check_SelectAllButton('.customer-read-all','.customer-read');

      $('.customer-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-read').prop('checked',false);
        }
      });
     Check_SelectAllButton('.customer-create-all','.customer-create');
      $('.customer-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-create').prop('checked',false);
        }
      });
     Check_SelectAllButton('.customer-update-all','.customer-update');
      $('.customer-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-update').prop('checked',false);
        }
      });
     Check_SelectAllButton('.customer-delete-all','.customer-delete');
      $('.customer-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-delete').prop('checked',false);
        }
      });


/*Customer*/

/*Vendor*/
	Check_SelectAllButton('.vendor-read-all','.vendor-read');
      $('.vendor-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-read').prop('checked',false);
        }
      });
	Check_SelectAllButton('.vendor-create-all','.vendor-create');
      $('.vendor-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-create').prop('checked',false);
        }
      });
    Check_SelectAllButton('.vendor-update-all','.vendor-update');
      $('.vendor-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-update').prop('checked',false);
        }
      });
     Check_SelectAllButton('.vendor-delete-all','.vendor-delete');
      $('.vendor-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-delete').prop('checked',false);
        }
      });
/*Vendor*/

/*Employees*/
	Check_SelectAllButton('.employee-read-all','.employee-read');
      $('.employee-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-read').prop('checked',false);
        }
      });
  	Check_SelectAllButton('.employee-create-all','.employee-create');
      $('.employee-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-create').prop('checked',false);
        }
      });
    Check_SelectAllButton('.employee-update-all','.employee-update');
      $('.employee-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-update').prop('checked',false);
        }
      });
    Check_SelectAllButton('.employee-delete-all','.employee-delete');
      $('.employee-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-delete').prop('checked',false);
        }
      });

/*Employees*/

/*Commission*/
	Check_SelectAllButton('.commission-read-all','.commission-read');
      $('.commission-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-read').prop('checked',false);
        }
      });

     Check_SelectAllButton('.commission-create-all','.commission-create');
      $('.commission-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-create').prop('checked',false);
        }
      });

      Check_SelectAllButton('.commission-update-all','.commission-update');
      $('.commission-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-update').prop('checked',false);
        }
      });

      Check_SelectAllButton('.commission-delete-all','.commission-delete');

      $('.commission-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-delete').prop('checked',false);
        }
      });

/*Commission*/
/*Zone*/
  Check_SelectAllButton('.zone-read-all','.zone-read');
      $('.zone-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.zone-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.zone-read').prop('checked',false);
        }
      });

     Check_SelectAllButton('.zone-create-all','.zone-create');
      $('.zone-create-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.zone-create').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.zone-create').prop('checked',false);
        }
      });

      Check_SelectAllButton('.zone-update-all','.zone-update');
      $('.zone-update-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.zone-update').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.zone-update').prop('checked',false);
        }
      });

      Check_SelectAllButton('.zone-delete-all','.zone-delete');

      $('.zone-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.zone-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.zone-delete').prop('checked',false);
        }
      });

/*Zone*/

});

function Check_SelectAllButton(class1,class2) {
	var total_length=$(class2).length;
	var checked_length=$(class2+':checked').length;
	if (total_length==checked_length) {
		$(class1).prop('checked', true);
	}
	else{
		$(class1).prop('checked', false);
	}
}