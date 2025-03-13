<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rean Web Admin Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <script src="js/jquer.js"></script>
  </head>

  <body>
    <div class="bar1">
      <ul>
        <li class="btn-menu">
          <i class="fas fa-bars"></i>
        </li>
        <li class="logo">
          <img src="img/logo.png" alt="">
        </li>
        <li class="title">
          Rean Web Admin Page
        </li>
        <li class="user">Rean Web</li>
        <li class="logout">
          <i class="fa-solid fa-power-off"></i>
        </li>
      </ul>
    </div>
    <div class="menu-box">
      <ul>
        <li data-opt="0">
          <a>Menu</a>
        </li>
        <li data-opt="1">
          <a>News</a>
        </li>
        <li data-opt="2">
          <a>Ads</a>
        </li>
      </ul>
    </div>
    <div class="container">
      <div class="bar2">
        <ul>
          <li class="btn btn-add-new">
            <i class="fas fa-plus"> </i> Add New
          </li>
          <li class="search-box">
            <input type="text" name="" id="txt-search-val">
            <select name="" id="txt-search-field">
              <!-- Options will be dynamically added here -->
            </select>
            <input type="button" value="Search" id="btn-search">
          </li>
        </ul>
        <ul>
          <li>
            <select name="" id="" class="num">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="50">50</option>
              <option value="60">60</option>
            </select>
          </li>
          <li class="btn btn-back">Back</li>
          <li class="page">
            <span class="current_page">1</span>/
            <span class="total_page">1</span>
            of
            <span class="total_data">0</span>
          </li>
          <li class="btn btn-next">Next</li>
        </ul>
      </div>
      <div class="data-container">
        <table id="tblData"></table>
      </div>
    </div>
    <!-- <div class="popup">
      a
    </div> -->
  </body>
  <script>
  $(document).ready(function() {
    var body = $('body');
    var tblData = $('#tblData');
    var popup = "<div class='popup'></div>";
    var frm = [
      "frm-menu.php",
      "frm-news.php",
      "frm-ads.php"
    ];
    var frmOpt = null;
    var s = 0;
    var e = $('.num').val();
    var curPage = $('.current_page');
    var totalPage = $('.total_page');
    var totalData = $('.total_data');
    var btnEdit = "<input type='button' value='Edit' class='btnEdit'>";
    var searchOpt = 0;
    var searchVal = ' ';
    var searchField = ' ';
    var trInd = 0;
    var searchCon = [{
        "id": "ID",
        "name": "Name",
        "status": "Status"
      },
      {
        "tbl_news.id": "ID",
        "tbl_menu.name": "Menu",
        "tbl_news.title": "Title",
      }
    ];

    $(".menu-box ul li").click(function() {
      var eThis = $(this);
      frmOpt = eThis.data("opt");

      var txt = ' <option value="0"> </option> ';
      for (var key in searchCon[frmOpt]) {
        txt += "<option value='" + key + "'>" + searchCon[frmOpt][key] + "</option>";
      }
      $('#txt-search-field').html(txt);

      $('.bar1').find('.title').html(eThis.find('a').html());
      $('.bar2').show();
      $(".menu-box ul li").css({
        "background-color": "black"
      });
      eThis.css({
        "background-color": "#333"
      });
      s = 0;
      curPage.text(1);
      searchField = 0;
      searchVal = ' ';
      searchField = ' ';
      if (frmOpt == 0) {
        get_menu();
      } else if (frmOpt == 1) {
        get_news();
      } else if (frmOpt == 2) {
        get_ads();
      }
    })

    //add form
    $('.btn-add-new').click(function() {
      body.append(popup);
      $(".popup").load("form/" + frm[frmOpt] + "", function(responseTxt, statusTxt, xhr) {
        if (statusTxt == "success")
          getAutoID();
        if (statusTxt == "error")
          alert("Error: " + xhr.status + ": " + xhr.statusText);
      });
    })
    //closs form
    body.on('click', '.frm .btn-close', function() {
      $('.popup').remove();
    })
    //save data
    body.on('click', '.frm .btn-save', function() {
      var eThis = $(this);
      if (frmOpt == 0) {
        save_menu(eThis);
      } else if (frmOpt == 1) {
        save_news(eThis);
      } else if (frmOpt == 2) {
        save_ads();
      }
      // $('.popup').remove();
    });

    //save menu
    function save_menu(eThis) {
      var Parent = eThis.parents('.frm');
      var id = Parent.find('#txt-id');
      var name = Parent.find('#txt-name');
      var status = Parent.find('#txt-status');
      var imgBox = Parent.find('.img-box');
      var photo = Parent.find('#txt-photo');
      var file = Parent.find('#txt-file');
      if (name.val() == '') {
        alert("please input name");
        name.focus();
        return;
      }

      var frm = eThis.closest('form.upl');
      var frm_data = new FormData(frm[0]);

      $.ajax({
        url: 'action/save-menu.php',
        type: 'post',
        data: frm_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
          eThis.html("<i class='fas fa-spinner fa-spin'></i> wait...");
          eThis.css({
            "pointer-events": "none",
            "opacity": "0.7"
          });
        },
        success: function(data) {
          if (data.dpl == true) {
            alert('Duplicate name');
            name.focus();
            eThis.html("save Change");
            eThis.css({
              "pointer-events": "auto",
              "opacity": "1"
            });
            return;
          }
          if (data.edit == true) {
            // alert("edit data");
            tblData.find('tr:eq(' + trInd + ') td:eq(1)').text(name.val());
            tblData.find('tr:eq(' + trInd + ') td:eq(2) img').attr("src", "img/product/" + photo.val() + "");
            tblData.find('tr:eq(' + trInd + ') td:eq(2) img').attr("alt", "" + photo.val() + "");
            tblData.find('tr:eq(' + trInd + ') td:eq(3)').text(status.val());
            eThis.html("save Change");
            eThis.css({
              "pointer-events": "auto",
              "opacity": "1"
            });
            $('.popup').remove();
            return;
          }
          var tr = "<tr>" +
            " <td>" + id.val() + "</td>" +
            " <td>" + name.val() + "</td> " +
            " <td> <img src='img/product/" + photo.val() + "'></td>" +
            " <td>" + status.val() + "</td>" +
            " <td>" + btnEdit + "</td>" +
            "</tr>";
          tblData.find('tr:eq(0)').after(tr);
          id.val(data.id + 1);
          name.val('');
          name.focus();
          photo.val('');
          file.val('');
          imgBox.css({
            "background-image": "url(img/logo.jpg)"
          })
          eThis.html("Save Change");
          eThis.css({
            "pointer-events": "auto",
            "opacity": "1"
          });
        }
      })
    }

    //save news
    function save_news(eThis) {
      var Parent = eThis.closest('.frm');
      var id = Parent.find('#txt-id');
      var title = Parent.find('#txt-title');
      var menu = Parent.find('#txt-menu');
      var status = Parent.find('#txt-status');
      var detail = Parent.find('#txt-detail');
      var imgBox = Parent.find('.img-box');
      var photo = Parent.find('#txt-photo');
      var file = Parent.find('#txt-file');
      var od = Parent.find('#txt-od');
      var location = Parent.find('#txt-location');

      if (title.val() == '') {
        alert("please input title");
        title.focus();
        return;
      }
      var frm = eThis.closest('form.upl');
      var frm_data = new FormData(frm[0]);

      $.ajax({
        url: 'action/save-news.php',
        type: 'POST',
        data: frm_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
          eThis.html("<i class='fas fa-spinner fa-spin'></i> wait...");
          eThis.css({
            "pointer-events": "none",
            "opacity": "0.7"
          });
        },
        success: function(data) {
          if (data.dpl == true) {
            alert('Duplicate name');
            title.focus();
            eThis.html("save Change");
            eThis.css({
              "pointer-events": "auto",
              "opacity": "1"
            });
            return;
          }
          if (data.edit == true) {
            // alert("edit data");
            tblData.find('tr:eq(' + trInd + ') td:eq(1)').text(Parent.find('#txt-menu :selected').text());
            tblData.find('tr:eq(' + trInd + ') td:eq(2)').text(title.val());
            tblData.find('tr:eq(' + trInd + ') td:eq(3) img').attr("src", "img/product/" + photo.val() + "");
            tblData.find('tr:eq(' + trInd + ') td:eq(3) img').attr("alt", "" + photo.val() + "");
            tblData.find('tr:eq(' + trInd + ') td:eq(4)').text(status.val());
            eThis.html("save Change");
            eThis.css({
              "pointer-events": "auto",
              "opacity": "1"
            });
            $('.popup').remove();
            return;
          }
          var tr = "<tr>" +
            " <td>" + data.id + "</td>" +
            " <td>" + Parent.find('#txt-menu :selected').text() + "</td>" +
            " <td>" + title.val() + "</td> " +
            " <td> <img src='img/product/" + photo.val() + "'></td>" +
            " <td>" + status.val() + "</td>" +
            " <td>" + btnEdit + "</td>" +
            "</tr>";
          tblData.find('tr:eq(0)').after(tr);
          id.val(data.id + 1);
          title.val('');
          menu.val('0');
          detail.val('');
          od.val('0');
          location.val('0');
          status.val('1');
          photo.val('');
          file.val('');
          imgBox.css({
            "background-image": "url(img/logo.jpg)"
          });

          eThis.html("Save Change");
          eThis.css({
            "pointer-events": "auto",
            "opacity": "1"
          });

          title.focus();
        }
      })
    }

    //save ads
    function save_ads() {
      alert('ads');
    }

    // get menu
    function get_menu() {
      var th = "<tr>" +
        "<th width='50'>ID</th>" +
        " <th>Name</th> " +
        "<th width='50'>Photo</th> " +
        "<th width='50'>Statsus</th>" +
        "<th width='50'>Action</th>" +
        "</tr> "
      tblData.empty();
      tblData.append(th);
      $.ajax({
        url: 'action/get-menu-json.php',
        type: 'post',
        data: {
          s: s,
          e: e,
          search_opt: searchOpt,
          searchVal: searchVal,
          searchField: searchField
        },
        cache: false,
        // processData: false,
        dataType: "json",
        success: function(data) {
          var num = data.length;
          var txt = ' ';
          for (i = 0; i < num; i++) {
            txt += "<tr>" +
              " <td>" + data[i].id + "</td>" +
              " <td>" + data[i].name + "</td> " +
              " <td> <img alt= '" + data[i].img + "' src='img/product/" + data[i].img + "'></td>" +
              " <td>" + data[i].status + "</td>" +
              " <td>" + btnEdit + "</td>" +
              "</tr>"
          }
          totalData.text(data[0].total);
          totalPage.text(Math.ceil(parseFloat(data[0].total / e)))
          tblData.append(txt);
        }
      })
    }
    //get news
    function get_news() {
      var th = "<tr>" +
        "<th width='50'>ID</th>" +
        " <th width='180'>Menu</th> " +
        "<th>Title</th> " +
        "<th width='50'>Photo</th> " +
        "<th width='50'>Statsus</th>" +
        "<th width='50'>Action</th>" +
        "</tr> "
      tblData.empty();
      tblData.append(th);
      $.ajax({
        url: 'action/get-news.php',
        type: 'post',
        data: {
          s: s,
          e: e,
          search_opt: searchOpt,
          searchVal: searchVal,
          searchField: searchField
        },
        cache: false,
        // processData: false,
        dataType: "json",
        success: function(data) {
          var num = data.length;
          var txt = ' ';
          for (i = 0; i < num; i++) {
            txt += "<tr>" +
              " <td>" + data[i].id + "</td>" +
              " <td>" + data[i].menu + "</td> " +
              " <td>" + data[i].title + "</td> " +
              " <td> <img alt= '" + data[i].img + "' src='img/product/" + data[i].img + "'></td>" +
              " <td>" + data[i].status + "</td>" +
              " <td>" + btnEdit + "</td>" +
              "</tr>"
          }
          totalData.text(data[0].total);
          totalPage.text(Math.ceil(parseFloat(data[0].total / e)))
          tblData.append(txt);
        }
      })
      // alert("get news");
    }
    //get ads
    function get_ads() {
      alert("get ads");
    }

    //get auto id
    function getAutoID() {
      $.ajax({
        url: 'action/get-auto-id.php',
        type: 'POST',
        data: {
          tbl: frmOpt
        },
        //contentType: false,
        cache: false,
        //processData: false,
        dataType: "json",

        success: function(data) {
          // alert(data.id);
          body.find('.frm #txt-id').val(data.id);
        }
      })
    }
    //upload Photo
    body.on('change', '.frm .txt-file', function() {
      var eThis = $(this);
      var Parent = eThis.parents('.frm');
      var frm = eThis.closest('form.upl');
      var frm_data = new FormData(frm[0]);
      var imgBox = Parent.find('.img-box');
      var photo = Parent.find('#txt-photo');

      $.ajax({
        url: 'action/upl-img.php',
        type: 'post',
        data: frm_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
          imgBox.css({
            "background-image": "url(img/product/" + data.imgName + ")"
          });
          photo.val(data.imgName);
        }
      })
    })
    //filter data
    $('.bar2').on('change', 'ul li .num', function() {
      e = $(this).val();
      s = 0;
      curPage.text(1);
      if (frmOpt == 0) {
        get_menu();
      } else if (frmOpt == 1) {
        get_news();
      } else if (frmOpt == 2) {
        get_ads();
      }

    })

    //next data
    $('.btn-next').click(function() {
      if (curPage.text() >= totalPage.text()) {
        return;
      }
      s = parseInt(s) + parseInt(e);
      curPage.text(parseInt(curPage.text()) + 1);
      if (frmOpt == 0) {
        get_menu();
      } else if (frmOpt == 1) {
        get_news();
      } else if (frmOpt == 2) {
        get_ads();
      }
    })
    //back data
    $('.btn-back').click(function() {
      if (curPage.text() == 1) {
        return;
      }
      s = parseInt(s) - parseInt(e);
      curPage.text(parseInt(curPage.text()) - 1);
      if (frmOpt == 0) {
        get_menu();
      } else if (frmOpt == 1) {
        get_news();
      } else if (frmOpt == 2) {
        get_ads();
      }
    })
    //search data
    $('#btn-search').click(function() {
      searchOpt = 1;
      searchVal = $('#txt-search-val').val();
      searchField = $('#txt-search-field').val();
      if (searchVal == '0') {
        return;
      } else if (searchField == ' ') {
        return;
      }
      if (frmOpt == 0) {
        get_menu();
      } else if (frmOpt == 1) {
        get_news();
      } else if (frmOpt == 2) {
        get_ads();
      }
    })
    //get edit val
    body.on('click', '.btnEdit', function() {
      var eThis = $(this);
      body.append(popup);
      $(".popup").load("form/" + frm[frmOpt] + "", function(responseTxt, statusTxt, xhr) {
        if (statusTxt == "success")
          if (frmOpt == 0) {
            get_edit_menu(eThis);
          } else if (frmOpt == 1) {
          get_edit_news(eThis);
        } else if (frmOpt == 2) {
          get_edit_ads(eThis);
        }
        if (statusTxt == "error")
          alert("Error: " + xhr.status + ": " + xhr.statusText);
      });
    })
    //get edit menu
    function get_edit_menu(eThis) {
      var Parent = eThis.parents('tr');
      var id = Parent.find('td:eq(0)').text();
      var name = Parent.find('td:eq(1)').text();
      var photo = Parent.find('td:eq(2) img').attr('alt');
      var status = Parent.find('td:eq(3)').text();
      body.find('.frm #txt-id').val(id);
      body.find('.frm #txt-edit-id').val(id);
      body.find('.frm #txt-name').val(name);
      body.find('.frm #txt-status').val(status);
      body.find('.frm #txt-photo').val(photo);
      body.find('.img-box').css("background-image", "url(img/product/" + photo + ")")
      trInd = Parent.index();
    }
    //get edit news
    function get_edit_news(eThis) {
      var Parent = eThis.parents('tr');
      var id = Parent.find('td:eq(0)').text();
      trInd = Parent.index();
      $.ajax({
        url: 'action/get-news-edit.php',
        type: 'POST',
        data: {
          id: id
        },
        // contentType: false,
        cache: false,
        // processData: false,
        dataType: "json",

        success: function(data) {
          body.find('.frm #txt-id').val(data.id);
          body.find('.frm #txt-edit-id').val(data.id);
          body.find('.frm #txt-menu').val(data.mid);
          body.find('.frm #txt-name').val(data.title);
          body.find('.frm #txt-detail').val(data.des);
          body.find('.frm #txt-photo').val(data.img);
          body.find('.frm #txt-od').val(data.od);
          body.find('.frm #txt-location').val(data.location);
          body.find('.frm #txt-status').val(data.status);
          body.find('.img-box').css("background-image", "url(img/product/" + data.img + ")");
        }
      })
    }
    //get edit ads
    function get_edit_ads(eThis) {
      alert('3')
    }
  })
  </script>

</html>