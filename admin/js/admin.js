jQuery(function ($) {
  $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
    if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });

  $("#close-sidebar").click(function() {
    $(".page-wrapper").removeClass("toggled");
  });
  $("#show-sidebar").click(function() {
    $(".page-wrapper").addClass("toggled");
  });
  $('#updateCategory').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-body #name').val(button.data('name'));
  });
  $('#deleteCategory').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-title').text("Xóa \"" + button.data('name') + "\"");
    modal.find('.modal-body p').text("Bạn có chắc chắn muốn xóa danh mục \"" + button.data('name') + "\"?");
  });
  $('#updateManufacturer').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-body #url').val(button.data('url'));
    modal.find('.modal-body #name').val(button.data('name'));
  });
  $('#deleteManufacturer').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-title').text("Xóa \"" + button.data('name') + "\"");
    modal.find('.modal-body p').text("Bạn có chắc chắn muốn xóa nhà cung cấp \"" + button.data('name') + "\"?");
  });
  $('#updateProduct').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-body #categoryId').val(button.data('categoryid'));
    modal.find('.modal-body #name').val(button.data('name'));
  });
  $('#deleteProduct').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    modal.find('.modal-body #id').val(button.data('id'));
    modal.find('.modal-title').text("Xóa \"" + button.data('name') + "\"");
    modal.find('.modal-body p').text("Bạn có chắc chắn muốn xóa nhà cung cấp \"" + button.data('name') + "\"?");
  });

  function updateUrlParameter(url, param, value){
    const regex = new RegExp('('+param+'=)[^\&]+');
    return url.replace( regex , '$1' + value);
  }

  $('#orderBy').change(function() {
    let url = window.location.href;
    if (url.indexOf('&orderBy=') > -1){
      return window.location.href = updateUrlParameter(url, 'orderBy', $(this).val());
    }
    if (url.indexOf('?') > -1){
      url += '&orderBy='
    }else{
      url += '?orderBy='
    }
    url += $(this).val();
    window.location.href = url;
  });

});
