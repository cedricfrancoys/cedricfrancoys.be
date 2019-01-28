<?phpnamespace test;use qinoa\orm\Model;class Example extends Model {    public static function getColumns() {        return array(            'string'			=> array('type' => 'string'),						            'short_text'		=> array('type' => 'text'),            'date_1'			=> array('type' => 'date'),            'date_2'			=> array('type' => 'date'),            'date_3'			=> array('type' => 'date'),				                        'float_1'			=> array('type' => 'float', 'onchange' => 'test\Example::onchangeTotalFloat'),            'float_2'			=> array('type' => 'float', 'onchange' => 'test\Example::onchangeTotalFloat'),	            'float_3'			=> array('type' => 'float', 'onchange' => 'test\Example::onchangeTotalFloat'),            'total_float'		=> array('type' => 'function', 'result_type' => 'float', 'function' => 'test\Example::getTotalFloat'),                        'integer_1'			=> array('type' => 'integer'),            'integer_2'			=> array('type' => 'integer'),	            'integer_3'			=> array('type' => 'function', 'result_type' => 'integer', 'store' => true, 'function' => 'test\Example::getInteger3' ),					                    );    }    public static function getDefaults() {        return array(            'date_1'			=> function() { return date("Y-m-d"); },            'date_2'			=> function() { return '0000-00-00'; },            'integer_1'			=> function() { return '54321'; }        );    }        public static function getTotalFloat($om, $uid, $oid, $lang) {        $total = 0.0;        $float_fields = array('float_1', 'float_2', 'float_3');        $res = $om->read($uid, 'test\Example', array($oid), $costs_fields, $lang);        foreach($float_fields as $float_field) $total += $res[$oid][$float_field];        return $total;    }    public static function onchangeTotalFloat($om, $uid, $oid, $lang) {        $om->update($uid, 'test\Example', array($oid), array('total_float' => Example::getTotalFloat($om, $uid, $oid, $lang)), $lang);    }        public static function getInteger3($om, $uid, $oid, $lang) {        return 12345;    }}