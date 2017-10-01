<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    //kalender

    if (isset($_GET['getfiveevents'])) {
      $Calendar->getFiveEvents();
    }

    if (isset($_GET['deleteevents'])) {
      $Calendar->deleteEvents($_GET['deleteevents']);
    }

    if (isset($_GET['updateevents'])) {
      $Calendar->updateEvents($_GET['updateevents'], $_GET['type'], $_GET['start'], $_GET['end'], $_GET['text']);
    }

    if (isset($_GET['getsimilarevents'])) {
      $Calendar->getSimilarEvents($_GET['getsimilarevents'], $_GET['type'], $_GET['start']);
    }

    if (isset($_GET['getcalendar'])) {
      $Calendar->filterData = false;
      $Calendar->getEvents();
    }

    if ($_GET['getcalendarfilter']) {

      if ($_GET['filterobject'] !== "null") {
          $Calendar->filterObject = explode(",", $_GET['filterobject']);
      } else {
          $Calendar->filterObject = NULL;
      }



      if ($_GET['filterjob'] !== "null") {
          $Calendar->filterJob = explode(",", $_GET['filterjob']);
      } else {
          $Calendar->filterJob = NULL;
      }

      if ($_GET['filterjob'] === "null" && $_GET['filterobject'] === "null") {
          $Calendar->filterData = false;
          $Calendar->getEvents();
      } else {
          $Calendar->filterData = true;
          $Calendar->getEvents();
      }


        //$Tasks->filterData($_GET['filteruser'], $_GET['filterstatus']);
    }


    //Objektid

    if (isset($_GET['getplans'])) {
        $Objects->getPlans($_GET['getplans']);
    }

    if (isset($_GET['deleteplan'])) {
        $Objects->deletePlan($_GET['deleteplan'], $_GET['planname']);
    }


    if (isset($_GET['getselectedvalues'])) {
        $Objects->getSelectedValues($_GET['getselectedvalues']);
    }

    //Kasutajalogi

    if (isset($_GET['getuserlog'])) {
      $login->getUserLog();
    }

    /*Autode bronni süsteem*/
    if (isset($_GET['blocked']) && isset($_GET['car']) && isset($_GET['start']) && isset($_GET['end'])) {
        $Timetable->getBlockedCars($_GET['car'], $_GET['start'], $_GET['end']);
    }

    if (isset($_GET['name']) && isset($_GET['car']) && isset($_GET['date']) && isset($_GET['hour']) && isset($_GET['carblock'])) {
        $Timetable->addTimeCars($_GET['name'], $_GET['car'], $_GET['date'], $_GET['hour']);
    }


    if (isset($_GET['date']) && isset($_GET['hour']) && isset($_GET['car']) && isset($_GET['delete'])) {
        $Timetable->deleteTimeCars($_GET['date'], $_GET['hour']);
    }
    /*Autode bronni süsteem lõpp*/

    /*Ruumide bronni süsteem*/
    if (isset($_GET['blockedrooms']) && isset($_GET['room']) && isset($_GET['start']) && isset($_GET['end'])) {
        $Timetable->getBlockedRooms($_GET['room'], $_GET['start'], $_GET['end']);
    }

    if (isset($_GET['name']) && isset($_GET['room']) && isset($_GET['date']) && isset($_GET['hour']) && isset($_GET['roomblock'])) {
        $Timetable->addTimeRooms($_GET['name'], $_GET['room'], $_GET['date'], $_GET['hour']);
    }

    if (isset($_GET['date']) && isset($_GET['hour']) && isset($_GET['roomdelete']) && isset($_GET['delete'])) {
        $Timetable->deleteTimeRooms($_GET['date'], $_GET['hour']);
    }
    /*Ruumide bronni süsteem lõpp*/


    if ($_GET['playarea']) {
        $Playgrounds->getCorrectPlay($_GET['playarea']);
    }

    if ($_GET['playdata']) {
        echo json_encode($Playgrounds->playData);
    }

    if ($_GET['playdocs']) {
        $Playgrounds->getPlayDocs($_GET['playdocs']);
    }

    if ($_GET['removedoc']) {
        $Playgrounds->removeDoc($_GET['removedoc']);
    }

    if ($_GET['deletepg']) {
        $Playgrounds->deletePlayground($_GET['deletepg']);
    }

    if ($_GET['deleteproperty']) {
        $Properties->deleteProperty($_GET['deleteproperty']);
    }

    if ($_GET['propertyarea']) {
        $Properties->filterProperties($_GET['propertyarea'], $_GET['filters']);
        //$Properties->getCorrectProperties($_GET['propertyarea'], $_GET['filters']);
    }

    if ($_GET['propertydata']) {
        echo json_encode($Properties->propertiesData);
    }

    if ($_GET['tenant']) {
        echo $Properties->getPropertyTenant($_GET['tenant']);
    }

    if ($_GET['tenantarchive']) {
        $Properties->getTenantArchive($_GET['tenantarchive']);
    }

    if ($_GET['propertydocs']) {
        $Properties->getPropertyDocs($_GET['propertydocs']);
    }

    if ($_GET['businessdocs']) {
        $Properties->getBusinessDocs($_GET['businessdocs']);
    }

    if ($_GET['deletetenant']) {
        $Properties->deleteTenant($_GET['deletetenant']);
    }

    //Objektide süsteem
    if ($_GET['objectdata']) {
        $Objects->getData($_GET['objectdata']);
    }

    if ($_GET['objectmeta']) {
        $Objects->getMeta($_GET['objectmeta']);
    }

    if ($_GET['addobject']) {
        $Objects->insertData($_GET['addobject'], $_GET['address'], $_GET['code'], $_GET['year'], $_GET['usedfor'], $_GET['contact'], $_GET['email'], $_GET['number']);
    }

    if ($_GET['getsubs']) {
        $Objects->getMaintanceSubs($_GET['getsubs']);
    }

    if ($_GET['maintancedocs']) {
        $Objects->getMaintanceDocs($_GET['maintancedocs']);
    }

    if ($_GET['getsubcalendar']) {
        $Objects->getSubCalendar($_GET['getsubcalendar']);
    }

    if ($_GET['objectdocs']) {
        $Objects->getDocs($_GET['objectdocs']);
    }

    if ($_GET['getform']) {
        $Objects->formFill($_GET['getform']);
    }

    if ($_GET['deletemaintance']) {
        $Objects->deleteMaintance($_GET['deletemaintance']);
    }

    if ($_GET['removemaintancedoc']) {
        $Objects->deleteMaintanceDocs($_GET['removemaintancedoc']);
    }

    /* Äripindade päringud */

    if ($_GET['getBusinessTenants']) {
        $Properties->getBusinessTenant($_GET['getBusinessTenants']);
    }

    if ($_GET['getBusinessData']) {
        echo json_encode($Properties->businessesData);
    }

    if ($_GET['deletebusiness']) {
        $Properties->deleteBusiness($_GET['deletebusiness']);
    }

    if ($_GET['updatebusiness']) {
        $Properties->updateBusiness($_GET['updatebusiness'], $_GET['name'], $_GET['address'], $_GET['condition'], $_GET['info']);
    }

    if ($_GET['deletebusinesstenant']) {
        $Properties->deleteBusinessTenant($_GET['deletebusinesstenant']);
    }

    if ($_GET['tenantbusinessarchive']) {
        $Properties->getBusinessTenantArchive($_GET['tenantbusinessarchive']);
    }

    /*IT support*/

    if ($_GET['getallitsupport']) {
        $IT_support->filterData = false;
        $IT_support->getData();
    }

    if ($_GET['getallitsupportfilter']) {

        if ($_GET['filterobject'] !== "null") {
            $IT_support->filterObject = explode(",", $_GET['filterobject']);
        } else {
            $IT_support->filterObject = NULL;
        }

        if ($_GET['filteruser'] !== "Kõik") {
            $IT_support->filterUser = $_SESSION['user_session'];
        } else {
            $IT_support->filterUser = NULL;
        }

        if ($_GET['filterstatus'] !== "null") {
            $IT_support->filterStatus = explode(",", $_GET['filterstatus']);
        } else {
            $IT_support->filterStatus = NULL;
        }

        if ($_GET['filteruser'] === "Kõik" && $_GET['filterstatus'] === "null" && $_GET['filterobject'] === "null") {
            $IT_support->filterData = false;
            $IT_support->getData();
        } else {
            $IT_support->filterData = true;
            $IT_support->getData();
        }

    }

    if ($_GET['deleteitsupport']) {
        $IT_support->deleteTask($_GET['deleteitsupport']);
    }

    if ($_GET['getitdatabyid']) {
        $IT_support->getDataById($_GET['getitdatabyid']);
    }

    /* Ülesannete päringud */

    if ($_GET['getmytasks']) {
        $Tasks->getMyTasks($_GET['start'], $_GET['end'], $_SESSION['user_session']);
    }

    if ($_GET['countmytasks']) {
        $Tasks->countMyTasks($_SESSION['user_session']);
    }

    if ($_GET['getalltasks']) {
        $Tasks->filterData = false;
        $Tasks->getData();
    }

    if ($_GET['getalltasksfilter']) {

        if ($_GET['filterobject'] !== "null") {
            $Tasks->filterObject = explode(",", $_GET['filterobject']);
        } else {
            $Tasks->filterObject = NULL;
        }

        if ($_GET['filteruser'] !== "Kõik") {
            $Tasks->filterUser = $_SESSION['user_session'];
        } else {
            $Tasks->filterUser = NULL;
        }

        if ($_GET['filterstatus'] !== "null") {
            $Tasks->filterStatus = explode(",", $_GET['filterstatus']);
        } else {
            $Tasks->filterStatus = NULL;
        }

        if ($_GET['filteruser'] === "Kõik" && $_GET['filterstatus'] === "null" && $_GET['filterobject'] === "null") {
            $Tasks->filterData = false;
            $Tasks->getData();
        } else {
            $Tasks->filterData = true;
            $Tasks->getData();
        }


        //$Tasks->filterData($_GET['filteruser'], $_GET['filterstatus']);
    }

    if ($_GET['getdatabyid']) {
      $Tasks->getDataByID($_GET['getdatabyid']);
    }

    if ($_GET['searchuser']) {
        $Tasks->searchUser($_GET['searchuser']);
    }


    if ($_GET['deletetask']) {
        $Tasks->deleteTask($_GET['deletetask']);
    }

    if ($_GET['getselectdata']) {

        if ($_GET['getselectdata'] === "1") {
            echo json_encode($Tasks->objectNames);
        } else if ($_GET['getselectdata'] === "2") {
            echo json_encode($Tasks->propertyNames);
        } else if ($_GET['getselectdata'] === "3") {
            echo json_encode($Tasks->businessNames);
        } else {
            echo json_encode($Tasks->playgroundNames);
        }
    }

    //ülesande mailile saatmine
//    if ($_GET['sendtask']) {
//        $Tasks->SendTask($_GET['sendtask']);
//    }

    // Service süsteem
    if ($_GET['servicedocs']) {
        $Service->getServiceDocs($_GET['servicedocs']);
    }

    if ($_GET['removeservicedoc']) {
        $Service->deleteServiceDocs($_GET['removeservicedoc']);
    }

    if ($_GET['getallitsupportfilter']) {

        if ($_GET['filterobject'] !== "null") {
            $IT_support->filterObject = explode(",", $_GET['filterobject']);
        } else {
            $IT_support->filterObject = NULL;
        }

        if ($_GET['filteruser'] !== "Kõik") {
            $IT_support->filterUser = $_SESSION['user_session'];
        } else {
            $IT_support->filterUser = NULL;
        }

        if ($_GET['filterstatus'] !== "null") {
            $IT_support->filterStatus = explode(",", $_GET['filterstatus']);
        } else {
            $IT_support->filterStatus = NULL;
        }

        if ($_GET['filteruser'] === "Kõik" && $_GET['filterstatus'] === "null" && $_GET['filterobject'] === "null") {
            $IT_support->filterData = false;
            $IT_support->getData();
        } else {
            $IT_support->filterData = true;
            $IT_support->getData();
        }

    }

    if ($_GET['getservicedata']) {
        $Service->filterData = false;
        $Service->getData();
    }

    if ($_GET['getservicedatafilter']) {

      if ($_GET['filterobject'] !== "null") {
          $Service->filterObject = explode(",", $_GET['filterobject']);
      } else {
          $Service->filterObject = NULL;
      }

      if ($_GET['filterstatus'] !== "null") {
          $Service->filterStatus = explode(",", $_GET['filterstatus']);
      } else {
          $Service->filterStatus = NULL;
      }

      if ($_GET['filterstatus'] === "null" && $_GET['filterobject'] === "null") {
          $Service->filterData = false;
          $Service->getData();
      } else {
          $Service->filterData = true;
          $Service->getData();
      }
    }

    if ($_GET['addnewcontact']) {
        $Service->insertNewContact($_GET['addnewcontact'], $_GET['name'], $_GET['field'], $_GET['phone'], $_GET['email'], $_GET['comments']);
    }

    if ($_GET['getservicecontacts']) {
        $Service->getContacts($_GET['getservicecontacts']);
    }

    if ($_GET['getservicedatabyid']) {
        $Service->GetServiceDataByID($_GET['getservicedatabyid']);
    }

    if ($_GET['deleteservice']) {
        $Service->deleteService($_GET['deleteservice']);
    }

    if ($_GET['deleteservicecontact']) {
        $Service->deleteContact($_GET['deleteservicecontact']);
    }



// Inspection süsteem

    if ($_GET['getinspectiondata']) {
        $Inspection->getData();
    }

    if ($_GET['getinspectiondatabyid']) {
        $Inspection->GetInspectionDataByID($_GET['getinspectiondatabyid']);
    }
    if ($_GET['deleteinspection']) {
        $Inspection->deleteInspection($_GET['deleteinspection']);
    }





}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['addmeta']) {
        $type = explode(" | ", $_POST['type']);
        $key = explode(" | ", $_POST['key']);
        $answer = explode(" | ", $_POST['answer']);

        for ($i = 0; $i < count($key); $i++) {
            $Objects->addMeta($_POST['addmeta'], $type[$i], $key[$i], $answer[$i]);
        }
    }

    if ($_POST['saveitsupportupdate']) {
        $IT_support->taskSendUpdate($_POST['saveitsupportupdate'], $_SESSION['user_session'], $_POST['long'], $_POST['status'], $_POST['solution']);
	$IT_support->updateData($_POST['saveitsupportupdate'], $_POST['object_type'], $_POST['object_id'], $_POST['location'], $_POST['tvid'], $_POST['short'], $_POST['date'], $_POST['priority'], $_POST['long'], $_POST['solution'], $_POST['user'], $_POST['deadline'], $_POST['status']);

    }

    if ($_POST['savetaskupdate']) {
        //$Tasks->taskSendUpdate($_POST['savetaskupdate'], $_SESSION['user_session'], $_POST['long'], $_POST['status'], $_POST['solution']);
        $Tasks->updateData($_POST['savetaskupdate'], $_POST['object_type'], $_POST['object_id'], $_POST['location'], $_POST['type'], $_POST['short'], $_POST['date'], $_POST['priority'], $_POST['source'], $_POST['long'],
        $_POST['solution'], $_POST['deadline'], $_POST['status']);

        $Tasks->editResponsibles($_POST['savetaskupdate'], $_POST['users'], $_POST['dones']);

        if($_POST['problem_tocalendar'] === "on") {
          if($_POST['object_type'] === "1") {
            $Calendar->insertData($_POST['object_id'], "Muu", $_POST['deadline'] . " 15:00", "", NULL, $_POST['long'], NULL);
          } else {
            $Calendar->insertData(NULL, "Muu", $_POST['deadline'] . " 15:00", "", NULL, $_POST['long'], NULL);
          }
        }


    }

    if ($_POST['saveserviceupdate']) {
        $Service->updateData($_POST['saveserviceupdate'], $_POST['object'], $_POST['name'], $_POST['field'], $_POST['contract'], $_POST['deadline'], $_POST['comments'], $_POST['pay'], $_POST['period']);
    }

    if ($_POST['saveservicecontact']) {
        $Service->updateContact($_POST['saveservicecontact'], $_POST['name'], $_POST['field'], $_POST['phone'], $_POST['email'], $_POST['comments']);
    }


    if ($_POST['editpg']) {
        $Playgrounds->updateData($_POST['editpg'], $_POST['address'], $_POST['contact'], $_POST['phone'], $_POST['attr']);
    }

    if ($_POST['editobj']) {
        $Objects->updateData($_POST['editobj'], $_POST['name'], $_POST['code'], $_POST['year'], $_POST['usedfor'], $_POST['address'], $_POST['contact'], $_POST['email'], $_POST['number']);
    }

    if ($_POST['editproperty']) {
        $Properties->updateData($_POST['editproperty'], $_POST['address'], $_POST['rooms'], $_POST['space'], $_POST['m2'], $_POST['koef'], $_POST['condition'], $_POST['info'], $_POST['forsale']);
    }

    if ($_POST['dotenant']) {
        $Properties->doTenant($_POST['dotenant'], $_POST['name'], $_POST['idn'], $_POST['number'], $_POST['email'], $_POST['real'], $_POST['contract'], $_POST['dhs'], $_POST['deadline']);
    }

    if ($_POST['dobusinesstenant']) {
        $Properties->doBusinessTenant($_POST['dobusinesstenant'], $_POST['name'], $_POST['reg'], $_POST['contact'], $_POST['phone'],
            $_POST['email'], $_POST['dhs'], $_POST['deadline'], $_POST['price'], $_POST['usedfor'], $_POST['info'], $_POST['tenant_id'],
            $_POST['addnew']);
    }

    if ($_POST['savemaintance']) {
        $Objects->saveMaintance($_POST['savemaintance'], $_POST['object'], $_POST['title'], $_POST['answer']);
    }

    if ($_POST['updatemaintance']) {
        $Objects->updateMaintance($_POST['updatemaintance'], $_POST['answer']);
    }

    if ($_POST['getmaintance']) {
        $Objects->getMaintance($_POST['getmaintance']);
    }

    if ($_POST['getmaintancearchive']) {
        $Objects->getMaintanceArchive($_POST['getmaintancearchive'], $_POST['objectid']);
    }


}
?>
