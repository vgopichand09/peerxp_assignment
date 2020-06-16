<!DOCTYPE html>
<html>
    <head>
        <!--<title>TODO supply a title</title>-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="assets/js/custom.js"></script>

    </head>
    <body>


        <?php
        include 'deskAPI.php';

        $ZOHODESK_API = new zohodeskAPI('2e4740934d006ac74de79025ce3ed073', 60001280952); //Replace your values

        /*         * **  Tickets      ***** */

        //Get tickets
        $ticketsJSON = $ZOHODESK_API->getTickets();

        if (!is_object($ticketsJSON)) {
            echo "No data avilable now or check the Network connection";
            return;
        }

        /* echo "<pre>";
          print_r($ticketsJSON);
          echo "</pre>"; */

        ob_start();
        ?>
        <div class="container">
            <button type="button" class="btn btn-info btn-lg float-right" data-toggle="modal" data-target="#myModal">Add new Ticket</button>
            <table id='myDataTable' class='display' style='width:100%'>
                <thead>
                    <tr>
                        <th>Ticket No</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Phone</th>
                        <th>Created on</th>
                        <th>DepartmentId</th>
                        <th>AssigneeId</th>
                        <th>Action</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    foreach ($ticketsJSON->data as $key => $ticket) {
                        echo '<tr id="' . $ticket->id . '">';
                        echo "<td>$ticket->ticketNumber</td>";
                        echo "<td>$ticket->email</td>";
                        echo "<td>$ticket->subject</td>";
                        echo "<td>$ticket->status</td>";
                        echo "<td>$ticket->phone</td>";
                        echo "<td>$ticket->createdTime</td>";
                        echo "<td>$ticket->departmentId</td>";
                        echo "<td>$ticket->assigneeId</td>";
                        echo '<td><a href="#" class="viewData" data-toggle="modal" data-target="#myModal" data-id="' . $ticket->id . '" style="padding: 0px 2px;">View</a></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        echo ob_get_clean();


        /*
          //Create a ticket
          $ticketFields = array(
          "subject"      => "My ticket subject",
          "contactId"    => 329372998233,    //YOUR CONTACT ID
          "departmentId" => 328739287873     //YOUR DEPARTMENT ID
          );
          //$createdTicket = $ZOHODESK_API->createTicket($ticketFields);
         */
        //Get a ticket
        //$ticket_id = 372662979823;
        //$ticketJSON = $ZOHODESK_API->getTicket($ticket_id);
        //Update a ticket
        //$ticket_id = 372662979823;
        //$createdTicket = $ZOHODESK_API->updateTicket($ticket_id, $ticketFields);
        //Delete a ticket
        //$ZOHODESK_API->deleteTicket($ticket_id);

        /*         * **  End of     Tickets      ***** */






        /*         * ** Contacts      ***** */

        //Get contacts
        //$contactsJSON = $ZOHODESK_API->getContacts();
        //Create a contact
        //$contactFields = array(
        //                    "lastName" => "Vijaaaay" 
        //                );
        //$createdContact = $ZOHODESK_API->createContact($contactFields);
        //echo json_encode($createdContact);
        //Get a contact
        //$contact_id = 372662979823;
        //$contactJSON = $ZOHODESK_API->getContact($contact_id);
        //Update a contact
        //$contact_id = 32838297938;
        //$updatedContact = $ZOHODESK_API->updateContact($contact_id, $contactFields);
        //Delete a contact
        //$contact_id = 32838297938;
        //$ZOHODESK_API->deleteContact($contact_id);

        /*         * **  End of     Contacts      ***** */



        /*         * *** Same for COMMENTS, ACCOUNTS, TASKS, AGENTS, DEPARTMENTS  *** */
        ?>


        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Ticket</h4>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="myForm" >
                            <table width="100%" class="table">
                                <tr>
                                    <th>Email:- </th>
                                    <td>
                                        <input type="email" id="pee_1" name="email"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Department:-
                                    </th>
                                    <td>
                                        <select name="departmentId" id="pee_6">
                                            <option value="7189000000051431">
                                                PWSLab Dev Ops Support
                                            </option>
                                            <option value="7189000001062045">
                                                iSupport
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Subject
                                    </th>
                                    <td>
                                        <input type="text" id="pee_2" name="subject" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Description
                                    </th>
                                    <td>
                                        <textarea name="description" id="pee_desc" rows="4"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Phone
                                    </th>
                                    <td>
                                        <input type="number" id="pee_4" name="phone" />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="status" value="open"/>
                            <input type="hidden" name="contactId" value="7189000001594001"/>
                            <input type="hidden" name="ticketId" id="ticketId" value=""/>
                            <input type="submit"  name="SubmitButton" id="subBut" value="Submit"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $message = "";
        if (isset($_POST['SubmitButton'])) { //check if form was submitted
            $ticket_fields = array(
                "email" => $_POST['email'], //get input text
                "departmentId" => $_POST['departmentId'],
                //"category" => $_POST['category'],
                //"webUrl" => $_POST['webUrl'],
                "subject" => $_POST['subject'],
                "description" => $_POST['description'],
                "phone" => $_POST['phone'],
                //"priority" => $_POST['priority'],
                //"status" => $_POST['status'],
                "contactId" => $_POST['contactId']
            );
            //echo $_POST['ticketId'];
            if (empty($_POST['ticketId'])) {
                $createdTicket = $ZOHODESK_API->createTicket($ticket_fields);
            }else{
                $createdTicket = $ZOHODESK_API->updateTicket($_POST['ticketId'], $ticket_fields);
            }
            //$message = "Success! You entered: " . $createdTicket;
            if ($createdTicket) {
                echo "Data inserted";
            }
        }
        ?>
    </body>
</html>