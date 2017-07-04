<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// Testscript for development
define('CLI_SCRIPT', true);

echo "begin" .PHP_EOL;

require_once($CFG->dirroot . '/local/evento/classes/evento_service.php');

echo "init service" .PHP_EOL;
$service = new local_evento_evento_service();

$event = $service->get_event_by_number("mod.bspWINF2.FS17_BS.001");
echo $event->idAnlass .PHP_EOL;

$enrolments = $service->get_enrolments_by_eventid($event->idAnlass);
echo $enrolments[0]->idPerson .PHP_EOL;

$person = $service->get_person_by_id($enrolments[0]->idPerson);
echo $person->personeMail .PHP_EOL;

?>
