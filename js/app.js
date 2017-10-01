(function(){
  "use strict";

  var App = function() {

    if(App.instance) {
      return App.instance;
    }

    this.rememberDocs = [];
    this.currentActive = 0;
    this.currentData = "";
    this.currentDocuments = "";
    this.rememberDocLinks = [];
    this.runDouble = false;
    this.usergroup = "0";

    App.instance = this;

    this.init();
    };

    window.App = App;


    App.prototype = {
      init: function() {

        window.setTimeout(function() {
          $(".alert").fadeTo(1500, 0).slideUp(500, function(){
            $(this).remove(); 
          });
        }, 5000);

        if(document.querySelector("#save_new_pg")) {
          var playground = new Playground();
        }
        if(document.querySelector("#mobile-data")) {
          var mobile = new Mobile();
          var mobedit = new MobEdit();
        }
        if(document.querySelector("#properties_select") || document.querySelector("#go-tenant")) {
          var properties = new Properties();
        }
        if(document.querySelector("#object_select")) {
          var objects = new Objects();
        }
        if(document.querySelector("#businesses_select")) {
          var business = new Business();
        }
        if(document.querySelector("#problemtable") || document.querySelector("#my_tasks")) {
          var tasks = new Tasks();
        }
        if(document.querySelector("#service")) {
          var service = new Service();
        }
        if(document.querySelector("#inspection")) {
          var inspection = new Inspection();
        }
        if(document.querySelector("#loggingtable")) {
          this.getLogData();
        }
        if(document.querySelector("#itsupporttable")) {
          var it_support = new IT_support();
        }
        if(document.querySelector("#calendar") && document.querySelector("#reserv_start") || document.querySelector("#next_events")) {
          var calendar = new Calendar();
        }

        this.listenEvents();
      },

      listenEvents: function() {

        if(document.querySelector("#property-filter")) {

          $('#ending-fil').on('ifChecked', function(event){
            Properties.instance.filterList.push("ending");
            Properties.instance.updatePropertySelect();
          });

          $('#ending-fil').on('ifUnchecked', function(event){
            for(var j = 0; j < Properties.instance.filterList.length; j++) {
              if(Properties.instance.filterList[j] === "ending") {
                Properties.instance.filterList.splice(j, 1);
                Properties.instance.updatePropertySelect();
              }
            }
          });

          $('#sale-fil').on('ifChecked', function(event){
            Properties.instance.filterList.push("sale");
            Properties.instance.updatePropertySelect();
          });

          $('#sale-fil').on('ifUnchecked', function(event){
            for(var j = 0; j < Properties.instance.filterList.length; j++) {
              if(Properties.instance.filterList[j] === "sale") {
                Properties.instance.filterList.splice(j, 1);
                Properties.instance.updatePropertySelect();
              }
            }
          });


          $('#free-fil').on('ifChecked', function(event){
            Properties.instance.filterList.push("free");
            Properties.instance.updatePropertySelect();
          });

          $('#free-fil').on('ifUnchecked', function(event){
            for(var j = 0; j < Properties.instance.filterList.length; j++) {
              if(Properties.instance.filterList[j] === "free") {
                Properties.instance.filterList.splice(j, 1);
                Properties.instance.updatePropertySelect();
              }
            }
          });


        }

        document.addEventListener("click", function(e) {

          if(document.querySelector("#usermodal")) {
            //console.log(e.target.parentElement.dataset.firstname);
            if(e.target.parentElement.className === "edit-user") {
              document.querySelector("#user_id").value = e.target.parentElement.dataset.id;
              document.querySelector("#user_name").value = e.target.parentElement.dataset.name;
              document.querySelector("#user_firstname").value = e.target.parentElement.dataset.firstname;
              document.querySelector("#user_lastname").value = e.target.parentElement.dataset.lastname;
              document.querySelector("#user_email").value = e.target.parentElement.dataset.email;
              document.querySelector("#user_group").value = e.target.parentElement.dataset.group;
              document.querySelector("#user_password").value = "";
              App.instance.getSelectedValues();
            }
          }


          /*if(e.target.parentElement.className === "filter-type") {
            e.target.parentElement.className += " active";
            Properties.instance.filterList.push(e.target.dataset.id);
            Properties.instance.updatePropertySelect();
          } else if(e.target.parentElement.className === "filter-type active") {
            e.target.parentElement.className = "filter-type";
            for(var j = 0; j < Properties.instance.filterList.length; j++) {
              if(Properties.instance.filterList[j] === e.target.dataset.id) {
                Properties.instance.filterList.splice(j, 1);
              }
            }
            Properties.instance.updatePropertySelect();
          }*/

          if(e.target.className === "glyphicon glyphicon-remove rmv-doc") {
            for(var i = 0; i < App.instance.currentDocuments.length; i++) {
              if(App.instance.currentDocuments[i].id === parseInt(e.target.dataset.id)) {
                var conf = confirm("Oled kindel, et soovid kustutada dokumendi " + App.instance.currentDocuments[i].name + "?");
                if(conf) {
                  e.target.parentElement.parentElement.remove();
                  App.instance.removeDoc(App.instance.currentDocuments[i].id);
                }
              }
            }
          }
        });



        /* Siin toimub üleslaadimise eventide kuulamised, juhul kui luua kaks üleslaadimist ühele lehele siis tuleb seda kopeerida */
        if(document.querySelector("#attach-btn")) {
          document.querySelector("#attach-btn").addEventListener("click", function() {
            document.querySelector("#attach-new").click();
          });
          document.querySelector("#attach-new").addEventListener("change", function() {
            App.instance.showAttachments(); // Kogu seda funktsiooni tuleb kopeerida, et näitaks õiges kohas üles laetavaid faile
          });

          //Frame listeniga katki midagi, upload viidud puhtale PHP kujule, ei lähe läbi iframe enam!
          /*document.querySelector("#add-attach").addEventListener("click", function() {
            document.querySelector("#doc_form").target = "my_iframe"; // Seda tõenäoliselt muutma ei pea
            document.querySelector("#doc_form").submit();

            var my_iframe = document.querySelector("#my_iframe");

            my_iframe.addEventListener("load", App.instance.frameListener());
            my_iframe.removeEventListener("load", App.instance.frameListener());


          });*/
          document.querySelector("#attach-newname").addEventListener("keyup", function() {
            if(document.querySelector("#attach-newname").value.length !== 0) {
              document.querySelector("#add-attach").disabled = false;
            } else {
              document.querySelector("#add-attach").disabled = true;
            }
          });
        }

        /* Üleslaadimise eventide lõpp */

      },

      getSelectedValues: function() {
        var id = document.querySelector("#user_id").value;
        console.log(id);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);

            if(data.rights !== null) {
              data = data.rights.split(',');
            }

            $('#select_rights_editing').val(data).trigger('change');
          }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getselectedvalues=" + id, true);
        xmlhttp.send();
      },


      frameListener: function() {
        this.runDouble = true;

        if(document.querySelector("#play_select")) {
        Playground.instance.getPlayDocs(App.instance.currentActive);
        }

        if(document.querySelector("#properties_select")) {
          Properties.instance.getPropertiesDocs(App.instance.currentActive);
        }


      },

      // See funktsioon paneb kuvama üles laetavaid faile, mitme uploadi puhul kopeerida fn ja muuta ID'd
      showAttachments: function() {
        var files = document.querySelector("#attach-new").files;
        var names = document.querySelector("#attach-names");
        console.log(files);
        names.innerHTML = "";

        for (var i = 0; i < files.length; i++) {
          names.innerHTML += files[i].name;
          if(i !== files.length - 1) {
            names.innerHTML += ", ";
          }
        }

      },

      removeDoc: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?removedoc=" + id, true);
        xmlhttp.send();

      },


      getLogData: function() {
        console.log("siin");
        var neededURL = "../inc/ajax.php?getuserlog=1";

        document.querySelector("#table_head").style.display = "table-header-group";

        var myTable = $('#loggingtable').DataTable({
          "language": {
            "decimal":        "",
            "emptyTable":     "Ei leitud vasteid",
            "info":           "Näitan vasteid _START_ kuni _END_. Kokku _TOTAL_ vastet",
            "infoEmpty":      "0 vastet leitud",
            "infoFiltered":   "(Otsitud _MAX_ vaste seast)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Näita _MENU_ vastet",
            "loadingRecords": "Laen...",
            "processing":     "Töötlen...",
            "search":         "Otsi:",
            "zeroRecords":    "Ei leitud mitte ühtegi vastet",
            "paginate": {
                "first":      "Esimene",
                "last":       "Viimane",
                "next":       "Järgmine",
                "previous":   "Eelmine"
              }
          },
          "ajax": {
                "url": neededURL,
                "dataSrc": "",


            },
          "columns": [
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Tasks_values" data-id="job-' + full.id + '">' + full.user + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Tasks_values" data-id="phone-' + full.id + '">' + full.ip + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Tasks_values" data-id="info-' + full.id + '">' + full.status + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Tasks_values" data-id="comment-' + full.id + '">' + full.logindate + '</span>';
                 }
                },
            ]

        });



      }






    };








}) ();
