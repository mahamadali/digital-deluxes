$(document).ready(function() {

    // Add item info line
    $("button.add-item-info-line").on("click", function() {
      addItemInfoLine();
    });

    // Remove item info line
    $(document).on("click", "button.remove-item-line", function() {
      removeItemInfoLine(getParent($(this)));
    });

    // Validate form on category select
    $(document).on("change", "select[name='category[]']", function() {
      validateQuoteItems();
    });

    // Set item additional info on item select
    $(document).on("change", "select[name='item[]']", function() {
      setItemInfo($(this));
      validateQuoteItems();
      calculateItemLines();
    });

    // Auto calculate the item lines on change of qty
    $(document).on("input", "input[name='qty[]']", function() {
      calculateItemLines();
    });

    // Auto calculate the item lines on change of price
    $(document).on("input", "input[name='price[]']", function() {
      calculateItemLines();
    });

    // Validate form before submit
    $("form[name='save-quote-items']").on("submit", function(e) {
    //   e.preventDefault();
      validateQuoteItems();
      if (!hasError()) {
        return true;
      }

      return false;
    });

  });

  function getParent(itemInfoLine) {
    return $(itemInfoLine).parent().parent();
  }

  function validateQuoteItems()
  {
    $("select, input, textarea").removeClass("error");
    $("div.choose-supplier-container").find("div.instructions").removeClass('m-0 mb-2').empty();

    $("select[name='category[]']").filter(function() {
        return !this.value;
    }).addClass("error");

    $("select[name='item[]']").filter(function() {
        return !this.value;
    }).addClass("error");

    $("input[name='qty[]']").filter(function() {
        return !this.value || isNaN(this.value) || this.value <= 0;
    }).addClass("error");

    $("input[name='price[]']").filter(function() {
      return !this.value || isNaN(this.value) || this.value <= 0;
    }).addClass("error");

    if ($("input[name='supplier[]']:checked").length == 0) {
        $("input[name='supplier[]']:first").addClass('error');
        $("div.choose-supplier-container").find("div.instructions").addClass('m-0 mb-2').html('<span class="mt-1 error">Please select at least one supplier to send this quote</span>');
    }

  }

  function hasError()
  {
    return $("select, input, textarea").hasClass('error');
  }

  function calculateItemLines() {
    var grandTotal = 0;
    $("input[name='qty[]']").each(function(key) {
      let parent = getParent($(this));
      let price = $(parent).find("input[name='price[]']").val();
      let qty = $(this).val();

      if (!isNaN(qty) && !isNaN(price)) {
        let total = parseFloat(qty * price);
        grandTotal += parseFloat(total);
        $(parent).find("input[name='amount[]']").val(total.toFixed(2));
      }

    });

    $("tr.item-info-summary-line").find("span.grand-total").html(grandTotal.toFixed(2));

  }

  function setItemInfo(object) {
    var parent = getParent($(object));
    var selectedItem = $(object).find('option:selected');
    $(parent).find("textarea[name='description[]'").val($(selectedItem).attr("data-description"));
    $(parent).find("textarea[name='specification[]'").val($(selectedItem).attr("data-specification"));
    $(parent).find("select[name='unit[]'").val($(selectedItem).attr("data-unit"));
    $(parent).find("input[name='qty[]'").val(1);
    $(parent).find("input[name='price[]'").val($(selectedItem).attr("data-price"));
    $(parent).find("img.item-picture").attr('src', $(selectedItem).attr("data-picture"));
  }

  function addItemInfoLine() {
    var itemRow = $("tr.item-info-line:first").clone();
    $(itemRow).find('input, select, textarea').val('');
    $(itemRow).find('img.item-picture').attr('src', APP_BASE_URL + 'assets/images/defaults/no_picture.png');
    $(itemRow).find('.select2-container').remove();
    $(itemRow).insertBefore($("tr.item-info-summary-line"));
    $("tr.item-info-line").find("select[name='item[]']:last").select2();
    calculateItemLines();
  }

  function removeItemInfoLine(itemInfoLine) {
    if ($("tr.item-info-line").length == 1) {
      return false;
    }
    $(itemInfoLine).remove();
    calculateItemLines();
  }