jQuery(document).ready(function ($) {
  $(".list-search").css('width',$('#search').width() + 26);

  $('#search').keyup(function (e) {
    const search = e.target.value.replace(/\s\s+/g, ' ');
    $(".list-search").css('display', search ? 'block' : 'none');
    fetch('/apis/product_api.php?endpoint=search&search=' + search)
      .then(res => res.json())
      .then((result) => {
        let html = '';
        result.map(product => {
          const index = cleanUpSpecialChars(product.name).toLocaleLowerCase().indexOf(cleanUpSpecialChars(search).toLocaleLowerCase());
          if (index > -1){
            product.name = product.name.substr(0, index) + '<b>' + product.name.substr(index, search.length) + '</b>' + product.name.substr(index + search.length, product.name.length);
          }
          html += `<div class="item-search">
                    <img class="item-search-logo" src="${product.logo}">
                    <a href="/product.php?id=${product.id}">${product.name} <span class="category">trong ${product.categoryName}</span></a>
                   </div>`
        });
        $('#list-search').html(html);
      })
      .catch(e => console.log(e))
  })
});
function cleanUpSpecialChars(str) {
  str = str !== null ? str.toString() : '';
  return str
    .replace(/[ÁÀẢÃẠĂẮẰẲẴẶÂẤẦẨẪẬ]/g,"A")
    .replace(/[áàảãạăắằẳẵặâấầẩẫậ]/g,"a")
    .replace(/[ÉÈẺẼẸÊẾỀỂỄỆ]/g,"E")
    .replace(/[éèẻẽẹêếềểễệ]/g,"e")
    .replace(/[Đ]/g,"D")
    .replace(/[đ]/g,"d")
    .replace(/[ÍÌỈĨỊ]/g,"I")
    .replace(/[íìỉĩị]/g,"i")
    .replace(/[ÓÒỎÕỌÔỐỒỔỖỘƠỚỜỞỠỢ]/g,"O")
    .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g,"o")
    .replace(/[ÚÙỦŨỤƯỨỪỬỮỰ]/g,"U")
    .replace(/[úùủũụưứừửữự]/g,"u")
    .replace(/[ÝỲỶỸỴ]/g,"Y")
    .replace(/[ýỳỷỹỵ]/g,"y")
    .replace(/[^a-z0-9 ]/gi,''); // final clean up
}
