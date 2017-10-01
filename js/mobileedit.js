(function(){
  "use strict";

  var MobEdit = function() {

    if(MobEdit.instance) {
      return MobEdit.instance;
    }


    MobEdit.instance = this;

    this.init();
    };

    window.MobEdit = MobEdit;

    MobEdit.prototype = {
      init: function() {
        this.fillSelect();
        this.watchSelect();
        this.listenClicks();
      },

      listenClicks: function() {
        document.querySelector("#save_data").addEventListener("click", this.listenSave.bind(this));
        document.querySelector("#save_edit").addEventListener("click", this.saveEdit.bind(this));


        document.addEventListener("click", function(e) {
          if(e.target.className === "del-btn" || e.target.parentElement.className === "del-btn") {
            var c = confirm("Kas oled kindel, et soovid kustutada numbri " + e.target.parentElement.dataset.number + "?");
            if(c){
              MobEdit.instance.removeData(e);
            }
          } else if(e.target.className === "edit-btn" || e.target.parentElement.className === "edit-btn") {
            MobEdit.instance.editData(e);
          }

        });


      },

      fillSelect: function() {
        document.querySelector("#add_org").innerHTML = document.querySelector("#org").innerHTML;
        document.querySelector("#add_dep").innerHTML = document.querySelector("#dep").innerHTML;
      },

      watchSelect: function() {
        document.querySelector("#add_org").addEventListener("change", function() {
          if(document.querySelector("#add_org").value === "Rae Vallavalitsus") {
            MobEdit.instance.showDepartment();
          } else {
            MobEdit.instance.hideDepartment();
          }
        });
      },

      showDepartment: function() {
        document.querySelector("#dep_group").style.visibility = "visible";
      },

      hideDepartment: function() {
        document.querySelector("#dep_group").style.visibility = "hidden";
      },

      listenSave: function() {

        var name = document.querySelector("#name").value;
        var org = document.querySelector("#add_org").value;
        var dep = document.querySelector("#add_dep").value;
        var phone = document.querySelector("#phone").value;
        var info = document.querySelector("#info").value;
        var comment = document.querySelector("#comment").value;
        var mail = document.querySelector("#mail").value;
        var job = document.querySelector("#job").value;
        var hidden = document.querySelector("#is_hidden").checked;
        if(hidden === true) {
          hidden = 1;
        } else {
          hidden = 0;
        }
        console.log(hidden);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
              document.querySelector("#name").value = "";
              document.querySelector("#add_org").value = "";
              document.querySelector("#add_dep").value = "";
              document.querySelector("#phone").value = "";
              document.querySelector("#info").value = "";
              document.querySelector("#comment").value = "";
              document.querySelector("#mail").value = "";
              document.querySelector("#job").value = "";
              document.querySelector("#is_hidden").checked = false;
              Mobile.instance.cleanTable();
              Mobile.instance.getData();
              $("#myModal").modal("hide");

            }
        };

        xmlhttp.open("GET", "../inc/mobile_functions.php?insertdata&name=" + name + "&org=" + org + "&dep=" + dep + "&phone=" + phone + "&info=" + info + "&comment=" + comment + "&mail=" + mail + "&job=" + job + "&hidden=" + hidden, true);
        xmlhttp.send();
      },

      removeData: function(e) {
        var id = e.target.dataset.id;
        if(id === undefined) {
          id = e.target.parentElement.dataset.id;
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
              Mobile.instance.getData();
              /*var rows = document.querySelectorAll(".row");
              var parent = document.querySelector("#mobile-data");
              for(var i = 0; i < rows.length; i++) {
                if(rows[i].dataset.id === id) {
                  parent.removeChild(rows[i]);
                }
              }*/
            }
        };

        xmlhttp.open("GET", "../inc/mobile_functions.php?delete=" + id, true);
        xmlhttp.send();
      },

      editData: function(e) {
        var id = e.target.dataset.id;
        if(id === undefined) {
          id = e.target.parentElement.dataset.id;
        }

        document.querySelector("#personal_id").value = id;

        var all_tds = document.querySelectorAll(".mobile_values");
        var name_edit = document.querySelector("#name_edit");
        var job_edit = document.querySelector("#job_edit");
        var phone_edit = document.querySelector("#phone_edit");
        var info_edit = document.querySelector("#info_edit");
        var comment_edit = document.querySelector("#comment_edit");
        //var hidden_edit = document.querySelector("#is_hidden_edit");
        var count = 0;

        while(all_tds.length > count) {
          if(all_tds[count].dataset.id === "name-" + id) {
            name_edit.value = all_tds[count].innerHTML;
            if(all_tds[count].dataset.hidden === "1") {
              //hidden_edit.checked = true;
              $('#is_hidden_edit').iCheck('check');
            } else if(all_tds[count].dataset.hidden === "0") {
              $('#is_hidden_edit').iCheck('uncheck');
              //hidden_edit.checked = false;
            }
          }
          if(all_tds[count].dataset.id === "job-" + id) {
            job_edit.value = all_tds[count].innerHTML;
          }
          if(all_tds[count].dataset.id === "phone-" + id) {
            phone_edit.value = all_tds[count].innerHTML;
          }
          if(all_tds[count].dataset.id === "info-" + id) {
            info_edit.value = all_tds[count].innerHTML;
          }
          if(all_tds[count].dataset.id === "comment-" + id) {
            comment_edit.value = all_tds[count].innerHTML;
          }

          count++;
        }

      },

      saveEdit: function() {
        var id = document.querySelector("#personal_id").value;
        var name_edit = document.querySelector("#name_edit");
        var job_edit = document.querySelector("#job_edit");
        var phone_edit = document.querySelector("#phone_edit");
        var info_edit = document.querySelector("#info_edit");
        var comment_edit = document.querySelector("#comment_edit");
        var hidden_edit = document.querySelector("#is_hidden_edit").checked;

        if(hidden_edit === true) {
          hidden_edit = 1;
        } else {
          hidden_edit = 0;
        }


        //console.log(id + " " + name_edit + " " + job_edit + " " + phone_edit + " " + info_edit + " " + comment_edit);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
              Mobile.instance.cleanTable();
              Mobile.instance.getData();
              $("#editmodal").modal("hide");
            }
        };

        xmlhttp.open("GET", "../inc/mobile_functions.php?edit=" + id + "&name=" + name_edit.value + "&job=" + job_edit.value + "&phone=" + phone_edit.value + "&info=" + info_edit.value + "&comment=" + comment_edit.value + "&hidden=" + hidden_edit, true);
        xmlhttp.send();
      }

    };

}) ();
