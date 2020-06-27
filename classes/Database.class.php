<?php
/**
 *  Copyright (C) <2016>  <Dogan Ucar>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Database {

    private $host       = "127.0.0.1";
    private $port       = "3306";
    private $user       = "root";
    private $database   = "case_based_reasoning";
    private $password   = "";
    private $connection = null;
    private $lastQuery  = "";
    private $mysqliResult;

    function __construct() {
        $this->connection = new mysqli ($this->host, $this->user, $this->password, $this->database, $this->port);
    }

    public function query($query) {
        $mysqliResult       = $this->connection->query($query);
        $this->lastQuery    = $query;
        $this->mysqliResult = $mysqliResult;
    }

    public function getMysqliArray($returnType = MYSQL_NUM) {
        return $this->mysqliResult->fetch_array($returnType);
    }

    public function getLastQuery() {
        return $this->lastQuery;
    }

    public function close() {
        $this->connection->close();
    }

}
