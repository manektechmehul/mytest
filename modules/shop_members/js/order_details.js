function addAllItemstoBag(coloursArrCSV) {
// this will not work ... need to add product to the colour and qty
    // will need a new basket function to allow multiple inserts
    // convert csv into arr
    coloursArr = coloursArrCSV.split(',');
    $.each(coloursArr, function() {
        $("#basket_all").append('<input type="text" value="' + $('#palette_txt_' + this).val() + '" name="palette_txt_' + this + ' "   id="palette_txt_' + this + ' "  >');
    });
}