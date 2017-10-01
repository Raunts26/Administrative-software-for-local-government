(function(){
  "use strict";

  var Business = function() {

    if(Business.instance) {
      return Business.instance;
    }

    this.businessTenants = [];
    this.addnew = false;
    this.tenantArchive = [];
    this.currentTenant = [];

    Business.instance = this;


    this.init();
    };

    window.Business = Business;


    Business.prototype = {
      init: function() {
        this.listenEvents();

      },

      listenEvents: function() {
        document.querySelector("#businesses_select").addEventListener("change", function() {
          Business.instance.getTenants();
          Business.instance.getData();
          Business.instance.changeButtonsStatus();

        });

        document.querySelector("#business_tenants").addEventListener("change", function() {
          Business.instance.getCorrectTenant();
          Business.instance.changeButtonsStatus();
          //Business.instance.buildTenantArchive();
        });

        document.querySelector("#delete_business").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada antud üüripinna?");

          if(c) {
            Business.instance.deleteBusiness();
          }
        });

        document.querySelector("#delete_tenant").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada antud rentniku?");

          if(c) {
            Business.instance.deleteTenant();
          }
        });

        document.querySelector("#save_business").addEventListener("click", function() {
          Business.instance.updateBusiness();
        });

        document.querySelector("#save_tenant").addEventListener("click", function() {
          Business.instance.doTenant();
        });

        document.querySelector("#add_rental").addEventListener("click", function() {
          Business.instance.resetTenantData();
          Business.instance.addnew = true;
          document.querySelector("#delete_tenant").style.display = "none";
          document.querySelector("#rental_heading").innerHTML = "Rentniku lisamine";
        });

        document.querySelector("#edit_rental").addEventListener("click", function() {
          Business.instance.getCorrectTenant();
          Business.instance.addnew = false;
          document.querySelector("#delete_tenant").style.display = "inline-block";
          document.querySelector("#rental_heading").innerHTML = "Rentniku muutmine";
        });

        document.querySelector("#clear_rental").addEventListener("click", function() {
          document.querySelector("#clear_rental").style.display = "none";
          Business.instance.changeButtonsStatus();
          Business.instance.getCorrectTenant();
        });

        document.addEventListener("click", function(e) {
          if(e.target.className === "archive_object" ||
           e.target.parentElement.className === "archive_object" ||
           e.target.className === "archive_object active_object" ||
           e.target.parentElement.className === "archive_object active_object") {
            Business.instance.showArchiveData(e.target.parentElement.dataset.id);
          }

        });

      },

      changeButtonsStatus: function() {
        var business = document.querySelector("#businesses_select");
        var tenant = document.querySelector("#business_tenants");
        var b_disabled = true;
        var t_disabled = true;

        if(business.value !== "0") {
          b_disabled = false;
        } else {
          b_disabled = true;
        }

        if(tenant.value !== "0") {
          t_disabled = false;
        } else {
          t_disabled = true;
        }

        document.querySelector("#edit_business").disabled = b_disabled;
        document.querySelector("#add_rental").disabled = b_disabled;
        document.querySelector("#attach-newname").disabled = b_disabled;
        document.querySelector("#attach-btn").disabled = b_disabled;
        document.querySelector("#add-attach").disabled = b_disabled;
        document.querySelector("#edit_rental").disabled = t_disabled;

      },

      getData: function() {
        var id = document.querySelector("#businesses_select").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);

              for(var i = 0; i < data.length; i++) {
                if(data[i].id === parseInt(id)) {
                  data = data[i];
                }
              }
              Business.instance.fillData(data);
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getBusinessData=" + id, true);
        xmlhttp.send();

      },

      fillData: function(data) {
        document.querySelector("#address_value").innerHTML = data.address;
        document.querySelector("#condition_value").innerHTML = data.condition;
        document.querySelector("#info_value").innerHTML = data.info;

        /* Muutmise vorm */
        document.querySelector("#id_edit").value = data.id;
        document.querySelector("#id_tenant").value = data.id;
        document.querySelector("#name_edit").value = data.name;
        document.querySelector("#address_edit").value = data.address;
        document.querySelector("#condition_edit").value = data.condition;
        document.querySelector("#info_edit").value = data.info;

        document.querySelector("#attach-id").value = data.id;
        document.querySelector("#attach-what").value = "business";

        this.getTenantArchive(data.id);
        Business.instance.getBusinessDocs(data.id);
      },

      deleteBusiness: function() {
        var id = document.querySelector("#id_edit").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log(this.responseText);
              var x = document.querySelector("#businesses_select").querySelectorAll("option");

              for(var i = 0; i < x.length; i++) {
                if(x[i].value === id){
                  document.querySelector("#businesses_select").removeChild(x[i]);
                }
              }

              Business.instance.getData();
              document.querySelector("#close_business").click();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deletebusiness=" + id, true);
        xmlhttp.send();
      },

      updateBusiness: function() {
        var id = document.querySelector("#id_edit").value;
        var name = document.querySelector("#name_edit").value;
        var address = document.querySelector("#address_edit").value;
        var condition = document.querySelector("#condition_edit").value;
        var info = document.querySelector("#info_edit").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log(this.responseText);

              var x = document.querySelector("#businesses_select").querySelectorAll("option");

              for(var i = 0; i < x.length; i++) {
                if(x[i].value === id){
                  x[i].innerHTML = name;
                }
              }

              Business.instance.getData();
              document.querySelector("#close_business").click();

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?updatebusiness=" + id + "&name=" + name + "&address=" + address + "&condition=" + condition + "&info=" + info, true);
        xmlhttp.send();
      },

      getTenants: function() {
        var id = document.querySelector("#businesses_select").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log(this.responseText);
              Business.instance.businessTenants = JSON.parse(this.responseText);
              Business.instance.buildTenantSelect();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getBusinessTenants=" + id, true);
        xmlhttp.send();
      },

      buildTenantSelect: function() {
        var tenants = document.querySelector("#business_tenants");
        tenants.innerHTML = "";

        var optiond = document.createElement("option");
        optiond.value = "0";
        optiond.innerHTML = "- Vali -";
        tenants.appendChild(optiond);


        for(var i = 0; i < this.businessTenants.length; i++) {
          var option = document.createElement("option");
          option.value = this.businessTenants[i].id;
          option.innerHTML = this.businessTenants[i].name;
          tenants.appendChild(option);
        }

      },

      getCorrectTenant: function() {
        var tenants = document.querySelector("#business_tenants");

        for(var i = 0; i < this.businessTenants.length; i++) {
          if(this.businessTenants[i].id === parseInt(tenants.value)) {
            this.fillTenantData(this.businessTenants[i]);
          }
        }
      },

      deleteTenant: function() {
        var id = document.querySelector("#tenant_real_id").value;
        var bid = document.querySelector("#id_tenant").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log(this.responseText);
              var z = document.querySelector("#business_tenants");
              var x = z.querySelectorAll("option");

              for(var i = 0; i < x.length; i++) {
                if(x[i].value === id){
                  z.removeChild(x[i]);
                }
              }

              Business.instance.getTenants();
              Business.instance.getTenantArchive(bid);
              document.querySelector("#close_tenant").click();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deletebusinesstenant=" + id, true);
        xmlhttp.send();
      },

      doTenant: function() {
        var id = document.querySelector("#id_tenant").value;
        var tenantid = document.querySelector("#tenant_real_id").value;
        var name = document.querySelector("#tenant_name").value;
        var reg = document.querySelector("#tenant_reg").value;
        var contact = document.querySelector("#tenant_contact").value;
        var phone = document.querySelector("#tenant_phone").value;
        var email = document.querySelector("#tenant_email").value;
        var dhs = document.querySelector("#tenant_dhs").value;
        var deadline = document.querySelector("#tenant_deadline").value;
        var price = document.querySelector("#tenant_price").value;
        var usedfor = document.querySelector("#tenant_usedfor").value;
        var info = document.querySelector("#tenant_info").value;
        var addnew = this.addnew;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              document.querySelector("#close_tenant").click();
              Business.instance.getTenants();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("dobusinesstenant=" + id + "&name=" + name + "&reg=" + reg + "&contact=" + contact + "&phone=" + phone + "&email=" +
         email + "&dhs=" + dhs + "&deadline=" + deadline + "&price=" + price + "&usedfor=" + usedfor + "&info=" + info + "&tenant_id=" + tenantid + "&addnew=" + addnew);

      },

      fillTenantData: function(data) {
        this.currentTenant = data;
        document.querySelector("#name_rent").innerHTML = data.name;
        document.querySelector("#id_rent").innerHTML = data.regcode;
        document.querySelector("#contact_rent").innerHTML = data.contact;
        document.querySelector("#number_rent").innerHTML = data.phone;
        document.querySelector("#mail_rent").innerHTML = data.email;
        document.querySelector("#dhs_rent").innerHTML = data.dhs;
        document.querySelector("#deadline_rent").innerHTML = data.deadline;
        document.querySelector("#price_rent").innerHTML = data.price;
        document.querySelector("#usage_rent").innerHTML = data.usedfor;
        document.querySelector("#info_rent").innerHTML = data.info;

        /* Muutmine */
        document.querySelector("#id_tenant").value = document.querySelector("#id_edit").value;
        document.querySelector("#tenant_real_id").value = data.id;
        document.querySelector("#tenant_name").value = data.name;
        document.querySelector("#tenant_reg").value = data.regcode;
        document.querySelector("#tenant_contact").value = data.contact;
        document.querySelector("#tenant_phone").value = data.phone;
        document.querySelector("#tenant_email").value = data.email;
        document.querySelector("#tenant_dhs").value = data.dhs;
        document.querySelector("#tenant_deadline").value = data.deadline;
        document.querySelector("#tenant_price").value = data.price;
        document.querySelector("#tenant_usedfor").value = data.usedfor;
        document.querySelector("#tenant_info").value = data.info;

      },

      resetTenantData: function() {
        document.querySelector("#tenant_real_id").value = "";
        document.querySelector("#tenant_name").value = "";
        document.querySelector("#tenant_reg").value = "";
        document.querySelector("#tenant_contact").value = "";
        document.querySelector("#tenant_phone").value = "";
        document.querySelector("#tenant_email").value = "";
        document.querySelector("#tenant_dhs").value = "";
        document.querySelector("#tenant_deadline").value = "";
        document.querySelector("#tenant_price").value = "";
        document.querySelector("#tenant_usedfor").value = "";
        document.querySelector("#tenant_info").value = "";
      },

      getTenantArchive: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);
              Business.instance.buildTenantArchive(data);
              Business.instance.tenantArchive = data;
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?tenantbusinessarchive=" + id, true);
        xmlhttp.send();
      },

      buildTenantArchive: function(data) {
        var element = document.querySelector("#tenant_archive");
        element.innerHTML = "";

        //console.log(this.currentTenant);

        if(this.currentTenant.length > 0) {

          var tra = document.createElement("tr");
          tra.className = "archive_object active_object";
          tra.dataset.id = this.currentTenant[0].id;
          element.appendChild(tra);

          var tda1 = document.createElement("td");
          tda1.innerHTML = this.currentTenant[0].regcode;
          tra.appendChild(tda1);

          var tda2 = document.createElement("td");
          tda2.innerHTML = this.currentTenant[0].deadline;
          tra.appendChild(tda2);

        }

        for(var i = 0; i < data.length; i++) {
          var tr = document.createElement("tr");
          tr.className = "archive_object";
          tr.dataset.id = data[i].id;
          element.appendChild(tr);

          var td1 = document.createElement("td");
          td1.innerHTML = data[i].regcode;
          tr.appendChild(td1);

          var td2 = document.createElement("td");
          td2.innerHTML = data[i].deadline;
          tr.appendChild(td2);
        }

      },

      showArchiveData: function(id) {
        var data = this.tenantArchive;


        if(this.currentTenant[0] === undefined || this.currentTenant[0].id !== parseInt(id)) {
          document.querySelector("#edit_rental").disabled = true;
          document.querySelector("#clear_rental").style.display = "inline-block";

          for(var i = 0; i < data.length; i++) {
            if(data[i].id === parseInt(id)) {

              document.querySelector("#name_rent").innerHTML = data[i].name;
              document.querySelector("#id_rent").innerHTML = data[i].regcode;
              document.querySelector("#contact_rent").innerHTML = data[i].contact;
              document.querySelector("#number_rent").innerHTML = data[i].phone;
              document.querySelector("#mail_rent").innerHTML = data[i].email;
              document.querySelector("#dhs_rent").innerHTML = data[i].dhs;
              document.querySelector("#deadline_rent").innerHTML = data[i].deadline;
              document.querySelector("#price_rent").innerHTML = data[i].price;
              document.querySelector("#usage_rent").innerHTML = data[i].usedfor;
              document.querySelector("#info_rent").innerHTML = data[i].info;


            }
          }
        } else {
          document.querySelector("#edit_rental").disabled = false;
          document.querySelector("#clear_rental").style.display = "none";

          document.querySelector("#name_rent").innerHTML = this.currentTenant[0].name;
          document.querySelector("#id_rent").innerHTML = this.currentTenant[0].regcode;
          document.querySelector("#contact_rent").innerHTML = this.currentTenant[0].contact;
          document.querySelector("#number_rent").innerHTML = this.currentTenant[0].phone;
          document.querySelector("#mail_rent").innerHTML = this.currentTenant[0].email;
          document.querySelector("#dhs_rent").innerHTML = this.currentTenant[0].dhs;
          document.querySelector("#deadline_rent").innerHTML = this.currentTenant[0].deadline;
          document.querySelector("#price_rent").innerHTML = this.currentTenant[0].price;
          document.querySelector("#usage_rent").innerHTML = this.currentTenant[0].usedfor;
          document.querySelector("#info_rent").innerHTML = this.currentTenant[0].info;

        }

      },

      getBusinessDocs: function(id) {
        var list = document.querySelector("#doc-list");
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
                      el[j].innerHTML += " <a href='" + data[i].link + "'>" + "<img src='images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
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

                  name.innerHTML = "<span class='glyphicon glyphicon-remove rmv-doc' data-id='" + data[i].id + "'></span> " + data[i].name + " <a href='" + data[i].link + "'>" + "<img src='images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                  App.instance.rememberDocs.push(data[i].name);

                  li.appendChild(name);
                  document.querySelector("#doc-list").appendChild(li);
                }




              }

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?businessdocs=" + id, true);
          xmlhttp.send();

        }
      },







    };


}) ();