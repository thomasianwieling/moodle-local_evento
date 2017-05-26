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

defined('MOODLE_INTERNAL') || die();

/**
 * Class definition for the evento webservice call
 *
 * @package    local_evento
 * @copyright  2017 HTW Chur Roger Barras
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class local_evento_evento_service {
    /**
     * Initialize the service keeping reference to the soap-client
     *
     * @param SoapClient $client
     */
    public function __construct($client = null) {
        global $CFG;
        $this->client = $client;

        $config = get_config('local_evento');

        // todo move to config setting
        $options = array(
            'location' => $config->wslocation,
            'uri' => $config->wsuri,
            'trace' => $config->wstrace,
            'login' => $config->wsusername,
            'password' => $config->wspassword
            // 'soap_version' => SOAP_1_2
        );
        // todo move to settings
        $wsdlfilename = 'evento_webservice_v1.wsdl';
        $wsdl = $CFG->dirroot . "/local/evento/wsdl/" . $wsdlfilename;

        if (!isset($client)) {
            $this->client = new SoapClient($wsdl, $options);
        }
    }

    /**
     * Doing a simple init Webservice call to open the connection
     * @return boolean true if the request was successfully
     */
    public function init_call() {
        try {
            $request['theEventoAnlassTypFilter']['idAnlassTyp'] = 1;
            $result = $this->client->listEventoAnlassTyp($request);
            return isset($result->return);
        } catch (Exception $ex) {
            debugging($ex->message);
            return false;
        }
    }

    /**
     * Obtains an event by the id-number
     * @param string $number the evento event-number like "mod.bspEA2.HS16_BS.001"
     * @return stdClass event object "EventoAnlass" definied in the wsdl
     */
    public function get_event_by_number($number) {
        // set request filter
        $request['theEventoAnlassFilter']['anlassNummer'] = $number;
        // to limit the response size if something went wrong
        $request['theLimitationFilter2']['theMaxResultsValue'] = 10;
        $result = $this->client->listEventoAnlass($request);
        return $result->return;
    }

    /**
     * Obtains the enrolments of an event
     * @param string $eventid the evento eventid
     * @return array of stdClass event object "EventoPersonenAnmeldung" definied in the wsdl
     */
    public function get_enrolments_by_eventid($eventid) {
        // set request filter
        $request['theEventoPersonenAnmeldungFilter']['idAnlass'] = $eventid;
        // to limit the response size if something went wrong
        $request['theLimitationFilter2']['theMaxResultsValue'] = 1000;
        $result = $this->client->listEventoPersonenAnmeldung($request);
        return $result->return;
    }

    /**
     * Obtains the person details
     * @param string $personid the evento eventid
     * @return stdClass person object "EventoPerson" definied in the wsdl
     */
    public function get_person_by_id($personid) {
        // set request filter
        $request['theEventoPersonFilter']['idPerson'] = $personid;
        // to limit the response size if something went wrong
        $request['theLimitationFilter2']['theMaxResultsValue'] = 10;
        $result = $this->client->listEventoPerson($request);
        return $result->return;
    }

}
