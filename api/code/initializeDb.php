<?php

function connectAsAdmin(){
    if (isset($_GET["198237569801798432579723401209375091"])){
        //$con = new mysqli("localhost", "root", "\$umm3rt1m3");
        $con = new mysqli("localhost", "easlern_campyadm", "\$umm3rt1m3");
        $con->query("use easlern_campy;");
        return $con;
    }
}
function connectAsWebUser(){
    $con = NULL;
    
    if (Utils::GetMode() == Utils::MODE_DEV){
        $con = new mysqli("localhost", "indiegam_cmpyusr", "\$umm3rt1m3");
        $con->query("use indiegam_campy;");
    }
    else{
        //$con = new mysqli("localhost", "root", "\$umm3rt1m3");
        $con = new mysqli("localhost", "easlern_campyusr", "\$umm3rt1m3"); 
        $con->query("use easlern_campy;");
    }
    return $con;
}

class Initializer{
    function go(){
        $con = connectAsAdmin();
        $con->query("drop table lineItems");
        $con->query("drop table siteBookings");
        $con->query("drop table reservations");
        $con->query("drop table users");
        $con->query("drop table customers");
        $con->query("drop table amenitiesAtSite");
        $con->query("drop table sites");
        $con->query("drop table amenities");
        $con->query("drop table campgrounds");
        $con->query("create table lineItems (id int not null auto_increment unique key primary key, reservationId int not null, correlationId varchar(24) not null unique key, description varchar(40) not null, amount decimal(6,2) not null, appliedDateTime timestamp not null default current_timestamp(), deleted int not null default 0, foreign key(reservationId) references reservations(id) on delete cascade)");
        $con->query("create table campgrounds (id int not null auto_increment unique key primary key, name varchar(40) not null, reservationEmail varchar(30) not null, mapImage blob not null, reservationInstructions varchar(300))");
        $con->query("create table users (id int not null auto_increment unique key primary key, campgroundId int not null, name varchar(40) not null, password varchar(64) not null, isAdmin int not null default 0, deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade);");
        $con->query("create table customers (id int not null auto_increment unique key primary key, campgroundId int not null, firstName varchar(40) not null, lastName varchar(40) not null, address varchar(120), phone varchar(30), licensePlate varchar(10), email varchar(40), notes varchar(2048), deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade);");
        $con->query("create table sites (id int not null auto_increment unique key primary key, campgroundId int not null, name varchar(40) not null, price decimal(6,2) not null, x double not null, y double not null, deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade)");
        $con->query("create table amenities (id int not null auto_increment unique key primary key, campgroundId int not null, name varchar(40) not null, deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade)");        
        $con->query("create table amenitiesAtSite (id int not null auto_increment unique key primary key, amenityId int not null, siteId int not null, deleted int not null default 0, foreign key(amenityId) references amenities(id) on delete cascade, foreign key(siteId) references sites(id) on delete cascade)");
        $con->query("create table reservations (id int not null auto_increment unique key primary key, campgroundId int not null, customerId int not null, startDate date not null, endDate date not null, confirmed int not null default 0, createdDateTime timestamp not null default current_timestamp(), deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade, foreign key(customerId) references customers(id) on delete cascade)");
        $con->query("create table siteBookings (id int not null auto_increment unique key primary key, reservationId int not null, siteId int not null, deleted int not null default 0, foreign key(reservationId) references reservations(id) on delete cascade, foreign key(siteId) references sites(id) on delete cascade)");
        
        $con->query("alter table campgrounds add startDateTime timestamp not null default current_timestamp();");
        $con->query("alter table campgrounds add monthsCovered int not null default 1;");
        $con->query("alter table siteBookings add checkedIn int not null default 0;");

        $con->query("alter table campgrounds modify reservationEmail varchar(100) not null");
        $con->query("alter table customers modify email varchar(100)");
        $con->query("alter table campgrounds modify mapImage mediumblob not null");
        $con->query("alter table campgrounds add subscriptionLevel int not null default 0");
        $con->query("alter table campgrounds add stripeCustomerId varchar(30) not null");
        
        $con->query("create table lineItemTypes (id int not null auto_increment unique key primary key, campgroundId int not null, name varchar(50) not null, cost decimal(6,2) not null default 0, recurringType int not null default 0, deleted int not null default 0, foreign key(campgroundId) references campgrounds(id) on delete cascade)");
        $con->query("alter table amenities add metered int not null default 0");
        $con->query("alter table amenities add costPerMeterUnit double null default null");
        $con->query("alter table lineItems add reversalId int null default null");
        $con->query("alter table lineItems add recurringType int not null default 0");
        $con->query("create table meters (id int not null auto_increment unique key primary key, reservationId int not null, amenitiesAtSiteId int not null, readingStart double not null, readingEnd double not null, createdDateTime timestamp not null default current_timestamp(), foreign key(amenitiesAtSiteId) references amenitiesAtSite(id) on delete cascade, unique index(reservationId, amenitiesAtSiteId))");
        $con->query("alter table lineItems add siteId int null default null");
        
        $con->query("alter table meters add deleted int not null default 0");
        $con->query("create table meterReadings (id int not null auto_increment unique key primary key, meterId int not null, reading double not null, createdDateTime timestamp not null default current_timestamp(), foreign key(meterId) references meters(id) on delete cascade)");
        $con->query("insert into meterReadings (meterId, reading, createdDateTime) select distinct id, readingEnd, createdDateTime from meters");
        $con->query("alter table meterReadings add deleted int not null default 0");
        $con->query("alter table meters drop readingEnd");
        
        $con->query("alter table siteBookings add totalSitePrice decimal(6,2) not null default 0;");
        $con->query("update siteBookings sb set totalSitePrice = (select datediff(endDate, startDate) from reservations r where r.id = sb.reservationId) * (select price from sites s where s.id = sb.siteId) where sb.totalSitePrice = 0;");
        
        $campgroundId = $this->CreateDemo("Nick's Rolling Meadows", "easlern@yahoo.com");
        Campgrounds::LoadDemoCampgroundMap($campgroundId);
        
        $users = new Users();
        $users->CreateUser($campgroundId, "admin", "\$umm3rt1m3", 1);
    }
    
    function CreateTrial($campgroundName, $reservationEmail, $loginName){
        if (trim($reservationEmail) == '') $reservationEmail = "campy_trial_email@bookingblocks.com";
        
        $con = connectAsWebUser();
        if ($con->connect_errno){
            printf("Couldn't connect to database. %s\n" , $con->connect_error);
        }
        else{
            #printf("Initialized database. %s", $con->error);
            
            $campground = new Campground($campgroundName, $reservationEmail, "", "", "");
            $campgroundId = Campgrounds::CreateNewCampground($campground);

            LogInfo("created trial campground with id " . $campgroundId);
            
            return $campgroundId;
        }
    }
    
    function CreateDemo($campgroundName, $reservationEmail){
        if (trim($reservationEmail) == '') $reservationEmail = "campy_demo_email@bookingblocks.com";
        
        $con = connectAsWebUser();
        if ($con->connect_errno){
            printf("Couldn't connect to database. %s\n" , $con->connect_error);
        }
        else{
            #printf("Initialized database. %s", $con->error);
            
            $statement = $con->prepare("insert into campgrounds (name, reservationEmail, mapImage, reservationInstructions) values (?, ?, ?, 'Thanks for using this demonstration version of Campy!')");
            if (!$statement){
                LogInfo($con->error);
                exit();
            }
            $mapImage = "";
            $statement->bind_param("sss", $campgroundName, $reservationEmail, $mapImage);
            $statement->execute();
            $campgroundId = $con->insert_id;
            LogInfo("created demo campground with id " . $campgroundId);
            $statement->close();
            
            $siteIds = new ArrayObject();
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Chestnut Cabin', 173./500, 204./500, 70)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Jack Pine Cabin', 140./500, 149./500, 70)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Cedar Cabin', 218./500, 144./500, 70)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Maple Cabin', 204./500, 73./500, 70)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 1', 209./500, 245./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 1', 209./500, 359./500, 20)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 2', 254./500, 203./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 2', 253./500, 406./500, 20)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 3', 296./500, 250./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 3', 308./500, 336./500, 20)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 4', 395./500, 292./500, 20)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 5', 378./500, 406./500, 20)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 4', 391./500, 248./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 5', 438./500, 181./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'RV 6', 365./500, 128./500, 50)");
            $siteIds->append($con->insert_id);
            $con->query("insert into sites (campgroundId, name, x, y, price) values ($campgroundId, 'Primitive 6', 437./500, 82./500, 20)");
            $siteIds->append($con->insert_id);
            
            $amenityIds = new ArrayObject();
            $con->query("insert into amenities (campgroundId, name) values ($campgroundId, 'Fire ring')");
            $amenityIds->append($con->insert_id);
            $con->query("insert into amenities (campgroundId, name, metered, costPerMeterUnit) values ($campgroundId, '15A Electric', 1, .25)");
            $amenityIds->append($con->insert_id);
            $con->query("insert into amenities (campgroundId, name) values ($campgroundId, 'Water')");
            $amenityIds->append($con->insert_id);
            $con->query("insert into amenities (campgroundId, name, metered, costPerMeterUnit) values ($campgroundId, '30A Electric', 1, .50)");
            $amenityIds->append($con->insert_id);
            $con->query("insert into amenities (campgroundId, name) values ($campgroundId, '30 Feet')");
            $amenityIds->append($con->insert_id);
            $con->query("insert into amenities (campgroundId, name) values ($campgroundId, '45 Feet')");
            $amenityIds->append($con->insert_id);
            
            for ($i = 0; $i < 4; $i++){
                for ($j = 0; $j < 3; $j++){
                    $con->query("insert into amenitiesAtSite (amenityId, siteId) values (" . $amenityIds[$j] . ", " . $siteIds[$i] . ")");
                }
            }
            for ($i = 4; $i < 5; $i++){
                for ($j = 0; $j < 2; $j++){
                    $con->query("insert into amenitiesAtSite (amenityId, siteId) values (" . $amenityIds[$j] . ", " . $siteIds[$i] . ")");
                }
            }
            $con->query("insert into amenitiesAtSite (amenityId, siteId) values (" . $amenityIds[1] . ", " . $siteIds[6] . ")"); // Site 7 gets left out of the loop for electric.
            $con->query("insert into amenitiesAtSite (amenityId, siteId) values (" . $amenityIds[1] . ", " . $siteIds[8] . ")"); // Site 9 gets left out of the loop for electric.
            
            for ($i = 5; $i < 16; $i++){
                for ($j = 0; $j < 1; $j++){
                    $con->query("insert into amenitiesAtSite (amenityId, siteId) values (" . $amenityIds[$j] . ", " . $siteIds[$i] . ")");
                }
            }
            
            $createdDateTime = "2013-03-20";
            $customers = new ArrayObject();
            
            $customer = new Customer($campgroundId, "Snyder", "Eric", "2122 South Street, Combes, WY", "555-230-9313", "123 AOE", $reservationEmail);
            $customers->append($customer);
            $customer = new Customer($campgroundId, "Emerson", "Michael", "3104 Hanley Lane, Aberdeen, NY", "555-810-1212", "712 MAY", $reservationEmail);
            $customers->append($customer);
            $customer = new Customer($campgroundId, "Thoreau", "Nick", "31 Howard Drive, White Rapids, CO", "555-370-3975", "393 ORE", $reservationEmail);
            $customers->append($customer);
            $customer = new Customer($campgroundId, "Whitman", "Sarah", "2583 West Landing SE, Pensacola, FL", "555-112-3654", "311 PBJ", $reservationEmail);
            $customers->append($customer);
            $customer = new Customer($campgroundId, "Audobon", "Andrea", "43 1/2 44th Street, Halpert, WA", "555-328-2889", "332 BAZ", $reservationEmail);
            $customers->append($customer);
            
            foreach ($customers as &$customer){
                Customers::CreateCustomer($customer);
            }
            
            $cable = new LineItemType($campgroundId, "Cable", 10, 1);
            LineItemTypes::Create($cable);
            $internet = new LineItemType($campgroundId, "Internet", 15, 1);
            LineItemTypes::Create($internet);
            
            //$sites = new ArrayObject(array($siteIds["1"]));
            //$reservation = new Reservation($campgroundId, $customer->GetId, $siteIds, $startDate, $endDate, $confirmed, "$createdDateTime");
            
            for ($x = 0; $x < 2; $x++){
                $siteIdsLeft = $siteIds->getArrayCopy();
                $siteIdsLeft = array_merge($siteIdsLeft, $siteIds->getArrayCopy());
                shuffle($siteIdsLeft);
                $customerArray = $customers->getArrayCopy();
                
                foreach($siteIdsLeft as $siteId){
                    $stayLength = rand(1, 4);
                    $offset = rand(-30, 30);
                    $customerPicked = rand(0, count($customers) - 1);
                    //$confirmed = rand(0,1);
                    $confirmed = 1;
                    $startSeconds = mktime(0, 0, 0, date("m"), date("d") + $offset, date("Y"));
                    $endSeconds = mktime(0, 0, 0, date("m"), date("d") + $offset + $stayLength, date("Y"));

                    $startDate = date("Y-m-d", $startSeconds);
                    $endDate = date("Y-m-d", $endSeconds);
                    
                    //LogInfo("startDate: $startDate endDate: $endDate");
                    
                    $lineItemTypes = LineItemTypes::GetAll($campgroundId);
                    $sites = new ArrayObject();
                    $sites->append($siteId);
                    $reservation = new Reservation($campgroundId, $customers[$customerPicked]->GetId(), $sites->getArrayCopy(), $startDate, $endDate, $confirmed, "2013-03-01");
                    if (!Reservations::HasBookingsForDateRange($campgroundId, $siteId, $startDate, $endDate)){
                        Reservations::Create($reservation);
                        
                        foreach($lineItemTypes as $service){
                            foreach($sites as $siteIdForService){
                                if (rand(0,2) == 0){
                                    $lineItem = new LineItem($reservation->GetId(), rand(0,1000000), $service->GetName(), $service->GetCost(), $service->GetRecurringType(), NULL, date("Y-m-d G:i:s"), $siteIdForService);
                                    LineItems::Create($lineItem);
                                }
                            }
                        }
                        
                        foreach($sites as $siteIdForMeter){
                            $site = Sites::GetSite($siteIdForMeter);
                            if ($site != NULL){
                                foreach($site->GetAmenities() as $amenityAtSite){
                                    $readingStart = rand(0,10000);
                                    $meter = new Meter($reservation->GetId(), $amenityAtSite->GetId(), $readingStart);
                                    Meters::Create($meter);
                                    for($x = 0; $x < 3; $x++){
                                        if (rand(0,1) == 1){ 
                                            $readingStart += rand(0,10);
                                            $reading = new MeterReading($meter->GetId(), $readingStart);
                                            MeterReadings::Create($reading);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $con->close();
            
            return $campgroundId;
        }
    }
}

?>