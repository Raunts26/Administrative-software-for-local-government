(function(){
  "use strict";

  var Service = function() {

    if(Service.instance) {
      return Service.instance;
    }

    this.neededURL = "";
    this.myTable = "";
  	this.editing = false;
  	this.service_id = 0;
    this.service_edit_id = 0;
    this.filterMe = false;
    this.filterStatus = [];
    this.filterObject = [];
    this.clickedRow = false;

    Service.instance = this;

    this.init();
    };

    window.Service = Service;


    Service.prototype = {
      init: function() {
        this.getData();
        this.listenEvents();
        this.getObjectsSelect();

        if(document.querySelector("#service_id").value.length > 0) {
          this.clickedRow = false;
          this.getUpdateData(document.querySelector("#service_id").value);
          this.getServiceDocs(document.querySelector("#service_id").value, true);
          $('[href=#company_files_edit]').tab('show');
        }

      },

      listenEvents: function() {


        /*$('#service').on('click', 'tr', function(e) {
          console.log(e.target);
          Service.instance.clickedRow = true;
          Service.instance.getUpdateData(this.childNodes[0].childNodes[0].dataset.id);
          $('#viewmodal').modal('show');
        });*/

        document.querySelector("#filter_services").addEventListener("click", function () {
          Service.instance.filterServices();
        });

        document.querySelector("#add_to_contact").addEventListener("click", function() {
          Service.instance.pullContactData();
        });

        document.querySelector("#add_to_contact_edit").addEventListener("click", function() {
          Service.instance.addNewContact();

        });

        document.querySelector("#save_edit").addEventListener("click", function() {
          Service.instance.saveUpdateData();
        });

        document.querySelector("#save_to_contact_edit").addEventListener("click", function() {
          Service.instance.saveUpdateContact();
        });

        $('#contacts_add_table').on('click', 'input[type="button"]', function(e){
           $(this).closest('tr').remove();
        });

        document.addEventListener("click", function(e) {

          if(e.target.className === "fa fa-address-book-o") {
            Service.instance.getContacts(e.target.dataset.id);
          }

          if(!$('#editmodal').hasClass('in')) {
            Service.instance.editing = false;
          } else {
            Service.instance.editing = true;
          }

          if(e.target.parentElement.className === "del-btn") {
            var c = confirm("Kas oled kindel, et soovid kustutada?");
            if(c) {
              Service.instance.deleteService(e.target.parentElement.dataset.id);
            }
          }

          if(e.target.parentElement.className === "edit-btn") {
            Service.instance.clickedRow = false;
            Service.instance.getUpdateData(e.target.parentElement.dataset.id);
            Service.instance.getServiceDocs(e.target.parentElement.dataset.id, true);
          }

          if(e.target.parentElement.className === "view-btn") {
            Service.instance.clickedRow = true;
            Service.instance.getUpdateData(e.target.parentElement.dataset.id);
            Service.instance.getServiceDocs(e.target.parentElement.dataset.id, false);

          }

        });

      },


      isModalOpen: function() {
        return $('.modal.in').length > 0;
      },



      filterServices: function () {
          this.filterObject = $("#object_filter").val();
          this.filterStatus = $("#status_filter").val();
          this.filterMe = true;


          this.myTable.destroy();
          this.getData();
      },

      checkIfEditContact: function() {
        var val = document.querySelector("#contact_edit_id");
        if(val.value.length > 0) {
          document.querySelector("#save_to_contact_edit").disabled = false;
        } else {
          document.querySelector("#save_to_contact_edit").disabled = true;

        }

      },

	   deleteService: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
               {
                  Service.instance.myTable.destroy();
                  Service.instance.getData();

                }
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deleteservice=" + id, true);
        xmlhttp.send();

      },
			getUpdateData: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);

              if(Service.instance.clickedRow === true) {
                Service.instance.fillViewData(data);
                Service.instance.getContacts(data.id);
                Service.instance.getObjectsSelect();

              } else {
                Service.instance.fillUpdateData(data);
                Service.instance.getContacts(data.id);

                Service.instance.service_edit_id = data.object;
                Service.instance.editing = true;
                Service.instance.getObjectsSelect();
              }


            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getservicedatabyid=" + id, true);
        xmlhttp.send();
      },

      getObjectsSelect: function () {
          var select = null;

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                  //console.log(this.responseText);
                  var data = JSON.parse(this.responseText);
                  Service.instance.fillObjectsSelect(data);
              }
          };
          xmlhttp.open("GET", "../inc/ajax.php?getselectdata=" + 1, true);
          xmlhttp.send();

      },

      fillObjectsSelect: function (data) {
          var select = null;

          if (this.editing) {
              select = document.querySelector("#object_service_edit");
          } else {
              select = document.querySelector("#object_service");
          }

          select.innerHTML = "";

          var selected = document.createElement("option");
          selected.value = "0";
          selected.innerHTML = "- Vali - ";
          select.appendChild(selected);

          for (var i = 0; i < data.length; i++) {
              var option = document.createElement("option");
              option.value = data[i].id;
              option.innerHTML = data[i].name;
              select.appendChild(option);
          }

          if (this.editing) {
              select.value = this.service_edit_id;
              //console.log(this.service_edit_id);

          }

      },

      getContacts: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);

              Service.instance.fillContacts(data);
              Service.instance.fillContactsEdit(data);

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getservicecontacts=" + id, true);
        xmlhttp.send();

      },

      pullContactData: function() {
        var name = document.querySelector("#c_name_add").value;
        //var field = document.querySelector("#c_field_add").value;
        var phone = document.querySelector("#c_phone_add").value;
        var email = document.querySelector("#c_email_add").value;
        var comments = document.querySelector("#c_comments_add").value;

        var what = [name, phone, email, comments];
        var what_string = ["name", "phone", "email", "comments"];

        var where = document.querySelector("#here_contacts");

        var tr = document.createElement("tr");
        tr.dataset.id = name + "-" + email;
        where.appendChild(tr);

        for(var i = 0; i < what.length; i++) {
          var td = document.createElement("td");
          td.innerHTML = what[i];
          tr.appendChild(td);

          var input = document.createElement("input");
          input.value = what[i];
          input.name = "c_" + what_string[i] + "[]";
          input.type = "hidden";
          tr.appendChild(input);

        }

        var td_delete = document.createElement("td");
        td_delete.innerHTML = '<span style="color: red;" role="button" class="fa fa-close" onClick="Service.instance.delPulledContact(this)"></span>';
        tr.appendChild(td_delete);


      },

      addNewContact: function() {
        var service = document.querySelector("#service_id").value;
        var name = document.querySelector("#c_name_edit").value;
        //var field = document.querySelector("#c_field_edit").value;
        var phone = document.querySelector("#c_phone_edit").value;
        var email = document.querySelector("#c_email_edit").value;
        var comments = document.querySelector("#c_comments_edit").value;
        var field = "";


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);

              document.querySelector("#contact_edit_id").value = "";
              document.querySelector("#c_name_edit").value = "";
              //document.querySelector("#c_field_edit").value = "";
              document.querySelector("#c_phone_edit").value = "";
              document.querySelector("#c_email_edit").value = "";
              document.querySelector("#c_comments_edit").value = "";

              Service.instance.checkIfEditContact();
              Service.instance.getContacts(service);
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?addnewcontact=" + service + "&name=" + name + "&field=" + field + "&phone=" + phone + "&email=" + email + "&comments=" + comments, true);
        xmlhttp.send();


      },

      delPulledContact: function(node) {
        var r = node.parentNode.parentNode;
        r.parentNode.removeChild(r);
      },

      delPulledContactDatabase: function(node) {
        var c = confirm("Kas oled kindel, et soovid kustutada?");
        if(c) {


          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {

                document.querySelector("#contact_edit_id").value = "";
                document.querySelector("#c_name_edit").value = "";
                //document.querySelector("#c_field_edit").value = "";
                document.querySelector("#c_phone_edit").value = "";
                document.querySelector("#c_email_edit").value = "";
                document.querySelector("#c_comments_edit").value = "";
                Service.instance.checkIfEditContact();


                var r = node.parentNode.parentNode;
                r.parentNode.removeChild(r);

              }
          };
          xmlhttp.open("GET", "../inc/ajax.php?deleteservicecontact=" + node.dataset.id, true);
          xmlhttp.send();

        }

      },


      fillContacts: function(data) {
        var mybody = document.querySelector("#contacts_body");
        mybody.innerHTML = "";

        for(var i = 0; i < data.length; i++) {
          var tr = document.createElement("tr");
          mybody.appendChild(tr);

          var td1 = document.createElement("td");
          td1.innerHTML = data[i].name;
          tr.appendChild(td1);

          var td2 = document.createElement("td");
          td2.innerHTML = data[i].phone;
          tr.appendChild(td2);

          var td3 = document.createElement("td");
          td3.innerHTML = data[i].email;
          tr.appendChild(td3);

          var td5 = document.createElement("td");
          td5.innerHTML = data[i].comments;
          tr.appendChild(td5);

        }

      },

      fillContactsEdit: function(data) {
        var mybody;
        if(Service.instance.clickedRow === true) {
          mybody = document.querySelector("#here_contacts_view");
        } else {
          mybody = document.querySelector("#here_contacts_edit");
        }
        mybody.innerHTML = "";

        for(var i = 0; i < data.length; i++) {
          var tr = document.createElement("tr");
          tr.className = "clickable";
          tr.dataset.id = data[i].id;
          tr.setAttribute('onclick','Service.instance.setEditContact(this)');
          mybody.appendChild(tr);

          var td1 = document.createElement("td");
          td1.innerHTML = data[i].name;
          tr.appendChild(td1);

          var td3 = document.createElement("td");
          td3.innerHTML = data[i].email;
          tr.appendChild(td3);

          var td2 = document.createElement("td");
          td2.innerHTML = data[i].phone;
          tr.appendChild(td2);

          var td5 = document.createElement("td");
          td5.innerHTML = data[i].comments;
          tr.appendChild(td5);

          var td_delete = document.createElement("td");
          td_delete.innerHTML = '<span data-id="' + data[i].id + '" style="color: red;" role="button" class="fa fa-close" onClick="Service.instance.delPulledContactDatabase(this)"></span>';
          tr.appendChild(td_delete);


        }


      },

      setEditContact: function(node) {
        var setid = document.querySelector("#contact_edit_id");
        var name = document.querySelector("#c_name_edit");
        //var field = document.querySelector("#c_field_edit");
        var phone = document.querySelector("#c_phone_edit");
        var email = document.querySelector("#c_email_edit");
        var comments = document.querySelector("#c_comments_edit");

        var what = [setid, name, email, phone, comments];

        for(var i = 0; i < what.length; i++) {
          if(i === 0) {
            what[i].value = node.dataset.id;
          } else {
            what[i].value = node.childNodes[i - 1].innerHTML;
          }
        }

        setid.value = node.dataset.id;

        Service.instance.checkIfEditContact();
      },


      fillViewData: function(data) {

        $("#object_service_view").val(data.object).trigger("change");
		    document.querySelector("#name_view").value = data.name;
        $("#field_view").val(data.field).trigger("change");
        //document.querySelector("#field_edit").value = data.field;
        document.querySelector("#contract_view").value = data.contract;

        var newdate = (moment(data.deadline).isValid()) ? moment(data.deadline).format("DD.MM.YYYY") : "Kuupäev puudu";

        document.querySelector("#deadline_view").value = newdate;
        document.querySelector("#comments_view").value = data.comments;
        document.querySelector("#pay_view").value = data.pay;
        document.querySelector("#period_view").value = data.period;

      },


      fillUpdateData: function(data) {
        console.log(data);
        document.querySelector("#service_id").value = data.id;
        document.querySelector("#attach-id").value = data.id;
        $("#object_service_edit").val(data.object).trigger("change");
		    document.querySelector("#name_edit").value = data.name;
        $("#field_edit").val(data.field).trigger("change");
        //document.querySelector("#field_edit").value = data.field;
        document.querySelector("#contract_edit").value = data.contract;

        var newdate = (moment(data.deadline).isValid()) ? moment(data.deadline).format("DD.MM.YYYY") : "Kuupäev puudu";

        document.querySelector("#deadline_edit").value = newdate;
        document.querySelector("#comments_edit").value = data.comments;
        document.querySelector("#pay_edit").value = data.pay;
        document.querySelector("#period_edit").value = data.period;

      },

      saveUpdateData: function() {
        var id = document.querySelector("#service_id").value;
        var object = document.querySelector("#object_service_edit").value;
        var name = document.querySelector("#name_edit").value;
        var field = document.querySelector("#field_edit").value;
        var contract = document.querySelector("#contract_edit").value;
        var deadline = document.querySelector("#deadline_edit").value;
        var comments = document.querySelector("#comments_edit").value;
        var pay = document.querySelector("#pay_edit").value;
        var period = document.querySelector("#period_edit").value;


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

              //console.log(this.responseText);
              document.querySelector("#close_edit").click();
              Service.instance.myTable.destroy();
              Service.instance.getData();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("saveserviceupdate=" + id + "&object=" + object + "&name=" + name + "&field=" + field + "&contract=" + contract + "&deadline=" + deadline + "&comments=" + comments + "&pay=" + pay + "&period=" + period);


	 },

   saveUpdateContact: function() {
     var service = document.querySelector("#service_id").value;
     var setid = document.querySelector("#contact_edit_id").value;
     var name = document.querySelector("#c_name_edit").value;
     //var field = document.querySelector("#c_field_edit").value;
     var phone = document.querySelector("#c_phone_edit").value;
     var email = document.querySelector("#c_email_edit").value;
     var comments = document.querySelector("#c_comments_edit").value;
     var field = "";

     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            document.querySelector("#contact_edit_id").value = "";
            document.querySelector("#c_name_edit").value = "";
            //document.querySelector("#c_field_edit").value = "";
            document.querySelector("#c_phone_edit").value = "";
            document.querySelector("#c_email_edit").value = "";
            document.querySelector("#c_comments_edit").value = "";

            Service.instance.checkIfEditContact();

            Service.instance.getContacts(service);


           }
       };
       xmlhttp.open("POST", "../inc/ajax.php", true);
       xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
       xmlhttp.send("saveservicecontact=" + setid + "&name=" + name + "&field=" + field + "&phone=" + phone + "&email=" + email + "&comments=" + comments);


    },


      getData: function() {
        if (this.filterMe) {
            if(this.filterObject === undefined) {
              this.filterObject = null;
            }
            this.neededURL = "../inc/ajax.php?getservicedatafilter=1&filterstatus=" + this.filterStatus + "&filterobject=" + this.filterObject;

        } else {
            this.neededURL = "../inc/ajax.php?getservicedata=1";
        }

        document.querySelector("#table_head").style.display = "table-header-group";

        /*if(this.myTable !== "") {
          this.myTable.destroy();
        }*/

        this.myTable = $('#service').DataTable({
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
                "url": Service.instance.neededURL,
                "dataSrc": "",


            },
          "columns": [
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Service_values" data-id="' + full.id + '">' + full.object_name + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Service_values" data-id="name-' + full.id + '">' + full.name + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                   if(full.sub !== null) {
                     var fixlength = (full.sub.length > 10) ? full.sub.substr(0, 10) + "." : full.sub;
                     return '<span class="Service_values" data-id="regnr-' + full.id + '" data-toggle="tooltip" data-placement="top" title="' + full.sub + '">' + fixlength + '</span>';

                   } else {
                     return '<span class="Service_values" data-id="regnr-' + full.id + '" data-toggle="tooltip" data-placement="top" title="' + full.main + '">' + fixlength + '</span>';

                   }
                 }
                },
                {
                 sortable: false,
                 "render": function ( data, type, full, meta ) {

                   return '<i data-id="' + full.id + '" style="font-size: 16px; margin-left: 10px;" tabindex="0" class="fa fa-address-book-o" role="button" data-toggle="modal" data-target="#contacts_modal"></i>';

                     /*$('[data-toggle="popover"]').popover();
                     console.log(full);

                     return '<i style="font-size: 16px; margin-left: 10px;" tabindex="0" class="fa fa-address-book-o" role="button" data-toggle="popover" title="Kontaktid" data-content="' + full.contact_name + " " + full.contact_email + " " + full.contact_phone + '"></i>';*/
                 }
                },
				 {
					 "render": function ( data, type, full, meta ) {
                     return '<span class="Service_values" data-id="description-' + full.id + '">' + full.contract + '</span>';
					 }
					  },
                {
					 "render": function ( data, type, full, meta ) {
                     return '<span class="Service_values" data-id="contract-' + full.id + '">' + full.pay  + "/" + full.period + '</span>';
					  }

                },
                {
                 sortable: false,
                 "render": function ( data, type, full, meta ) {
                     return '<span class="view-btn" data-id="' + full.id + '" data-toggle="modal" data-target="#viewmodal"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></span> <span class="edit-btn" data-id="' + full.id + '" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span> <span class="del-btn" data-id="' + full.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>';
                 }
                }

                /*
                <span class="edit-btn" data-id="23" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>
                <span class="del-btn" data-id="23"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>

                */
                /*{ "defaultContent": '<span class="edit-btn" data-toggle="modal" data-id="' + "id" + '" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>' }*/
            ]

        });



        //Näita tulbad kui oled admin
        /*if(App.instance.usergroup === "3" || App.instance.usergroup === "4") {
          this.myTable.column(3).visible(true); // Paketi tulp
          this.myTable.column(5).visible(true); // Halda tulp
        }*/

      },

      getServiceDocs: function(id, edit) {
        var list = (edit) ? document.querySelector("#doc-list") : document.querySelector("#doc-list-view");
        /*if(edit) {
          list = document.querySelector("#doc-list");
        } else {
          list = document.querySelector("#doc-list-view");
        }*/
        console.log(id);

        App.instance.rememberDocs = [];
        App.instance.currentDocuments = [];
        list.innerHTML = "";
        App.instance.rememberDocLinks = [];

        //console.log(App.instance.rememberDocs);

        if(id !== 0) {

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

              App.instance.currentDocuments = JSON.parse(this.responseText);
              var data = App.instance.currentDocuments;

              for(var i = 0; i < data.length; i++) {
                var exists = false;

                for(var k = 0; k < App.instance.rememberDocs.length; k++) {
                  if(data[i].name === App.instance.rememberDocs[k]) {
                    exists = true;
                  }
                }



                if(exists) {
                  var el = document.querySelectorAll(".doc-element");

                  for(var j = 0; j < el.length; j++) {
                    if(el[j].dataset.name === data[i].name) {
                      el[j].innerHTML += " <a href='" + data[i].link + "'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                    }

                    if(App.instance.runDouble) {
                      for(var l = 0; l < el[j].childNodes.length; l++) {
                        var is = false;

                        if(el[j].childNodes[l].nodeType === 1) {
                          //console.log(el[j].childNodes[l].getAttribute("href"));

                          if(el[j].childNodes[l].getAttribute("href") === data[i].link) {

                            for(var a = 0; a < App.instance.rememberDocLinks.length; a++) {
                              if(App.instance.rememberDocLinks[a] === data[i].link) {
                                is = true;
                              }
                            }

                            if(!is) {
                              App.instance.rememberDocLinks.push(data[i].link);
                              //console.log(App.instance.rememberDocLinks);
                              el[j].childNodes[l].remove();


                            }
                          }
                        }
                      }
                    }

                  }

                } else {
                  var name =  document.createElement("span");
                  var li = document.createElement("li");
                  name.dataset.name = data[i].name;
                  name.className += " doc-element";

                  name.innerHTML = "<span style='color: #ec0202' class='glyphicon glyphicon-remove' data-id='" + data[i].id + "' onclick='Service.instance.removeDoc(event, " + data[i].id + ")'></span> " + data[i].name + " <a href='" + data[i].link + "'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                  App.instance.rememberDocs.push(data[i].name);

                  li.appendChild(name);
                  list.appendChild(li);
                }




              }

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?servicedocs=" + id, true);
          xmlhttp.send();

        }
      },

      removeDoc: function(e, id) {
        var c = confirm("Kas oled kindel, et soovid kustutada?");

        if(c) {
          e.target.parentElement.remove();

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?removeservicedoc=" + id, true);
          xmlhttp.send();

        }

      },





    };








}) ();
