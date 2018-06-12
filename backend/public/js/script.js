$(document).ready(function () {
   let addRoomBtn = $("#add-room");
   let loadRoomBtn = $("#load-rooms");
   let titleInput = $("#room-title");
   let roomsTable = $("table tbody");
   
   function updateRooms() {
     let rooms = [];
     $.getJSON("api/rooms", function (data) {
       $.each(data, function (index, room) {
         rooms.push(createRoom(room));
       });
       roomsTable.empty();
       roomsTable.append(rooms);
     });
   }
   
   function createRoom(room) {
     let tr = $("<tr>");
     let title = $("<td>", {
       text: room.title 
     }).appendTo(tr);
     let created = $("<td>", {
       text: room.created
     }).appendTo(tr);
     let lock = $("<td>", {
       text: room.lock ? "Ano" : "Ne"
     }).appendTo(tr);
     return tr;
   }
   
   loadRoomBtn.click(function () {
     updateRooms();
   });
   
   updateRooms();
   setInterval(updateRooms, 5000);
   
   addRoomBtn.click(function () {
     let title = titleInput.val();
     if (title) {
       $.post("api/rooms", {title: title}, function () {
         titleInput.val("");
         updateRooms();
       });
     } else {
       alert("Zadejte název nové místnosti");
     }
   });

});