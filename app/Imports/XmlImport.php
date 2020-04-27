<?php

namespace App\Imports;

use App\Modules\Customers\Models\Suspect;
use App\Modules\Customers\Models\SuspiciousOrganizations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SimpleXMLElement;

class XmlImport
{
    public static function import(Request $request)
    {
        $string = file_get_contents($request->file('file')->getPathname());
        $xml = new SimpleXMLElement($string);
        foreach ($xml->xpath('//INDIVIDUALS/INDIVIDUAL') as $t) {

            $birth_date = empty($t->children()->INDIVIDUAL_DATE_OF_BIRTH->DATE) ? $t->children()->INDIVIDUAL_DATE_OF_BIRTH->YEAR : $t->children()->INDIVIDUAL_DATE_OF_BIRTH->DATE;
            // gather data for other column
            $others = "";
            $others .= 'birth date: ';
            foreach ($t->children()->INDIVIDUAL_DATE_OF_BIRTH as $a) {
                $others .= $a->TYPE_OF_DATE . ' ';
                $others .= $a->DATE . ' ';
                $others .= $a->YEAR . ' ';
                $others .= $a->NOTE . ' ';
            }
            $others .= 'places of birth: ';
            foreach ($t->children()->INDIVIDUAL_PLACE_OF_BIRTH as $a) {
                $others .= $a->CITY;
                $others .= $a->STATE_PROVINCE;
                $others .= $a->COUNTRY . ' - ';
            }
            $others .= 'documents: ';
            foreach ($t->children()->INDIVIDUAL_DOCUMENT as $a) {
                $others .= $a->TYPE_OF_DOCUMENT;
                $others .= $a->TYPE_OF_DOCUMENT2;
                $others .= $a->NUMBER . ' - ';
            }
            $others .= 'INDIVIDUAL_ALIAS: ';
            foreach ($t->children()->INDIVIDUAL_ALIAS as $a) {
                $others .= $a->QUALITY;
                $others .= $a->ALIAS_NAME;
                $others .= $a->NOTE . ' - ';
                $others .= $a->DATE_OF_BIRTH . '';
            }
            $others .= $t->children()->UN_LIST_TYPE . ' - ';
            $others .= $t->children()->REFERENCE_NUMBER . ' - ';
            $others .= $t->children()->LISTED_ON . ' - ';
            $others .= $t->children()->COMMENTS1 . ' - ';
            $others .= $t->children()->NATIONALITY->VALUE . ' - ';
            $others .= $t->children()->LIST_TYPE->VALUE . ' - ';
            $others .= $t->children()->LAST_DAY_UPDATED->VALUE . ' - ';
            $others .= 'INDIVIDUAL_ADDRESS: ';
            foreach ($t->children()->INDIVIDUAL_ADDRESS as $a) {
                $others .= $a->STREET;
                $others .= $a->COUNTRY;
                $others .= $a->CITY;
                $others .= $a->STATE_PROVINCE;
            }
/////////
            $concatenated_names = trim($t->children()->FIRST_NAME) . trim($t->children()->SECOND_NAME) . trim($t->children()->THIRD_NAME) . trim($t->children()->FOURTH_NAME);
            $concatenated_names = preg_replace('/[^A-Za-z0-9]/', '', $concatenated_names);
            $concatenated_names = mb_strtolower($concatenated_names);

            $suspect = new Suspect([
                'concatenated_names' => ($concatenated_names),
                'first_name' => $t->children()->FIRST_NAME,
                'second_name' => $t->children()->SECOND_NAME,
                'third_name' => $t->children()->THIRD_NAME,
                'fourth_name' => $t->children()->FOURTH_NAME,
                'organization' => 'UN',
                'birth_date' => Carbon::parse($birth_date),
                'others' => $others,
            ]);
            $suspect->save();
        }
        foreach ($xml->xpath('//ENTITIES/ENTITY') as $x) {
            $organization_concat_name = preg_replace('/[^A-Za-z0-9]/', '', trim($x->children()->FIRST_NAME));
            $organization_concat_name = mb_strtolower($organization_concat_name);
            $others = "";
            $others .= $x->UN_LIST_TYPE;
            $others .= $x->REFERENCE_NUMBER;
            $others .= $x->LISTED_ON;
            $others .= $x->DATAID;
            $others .= $x->VERSIONNUM;
            foreach ($x->children()->LAST_DAY_UPDATED as $a) {
                $others .= $a->VALUE . ' ';
            }

            $list_type = '';
            foreach ($x->children()->LIST_TYPE as $a) {
                $list_type = $a->VALUE . ' ';
            }
            $address = '';
            foreach ($x->children()->ENTITY_ADDRESS as $a) {
                $address .= $a->STREET . ' ';
                $address .= $a->CITY . ' ';
                $address .= $a->COUNTRY . ' ';
                $address .= $a->NOTE . ' ';
            }
            $aliases = '';
            foreach ($x->children()->ENTITY_ALIAS as $a) {
                $aliases .= $a->QUALITY . ' ';
                $aliases .= $a->ALIAS_NAME . ' ';
            }

            $suspiciousOrganizations = new SuspiciousOrganizations([
                'concatenated_name' => $organization_concat_name,
                'organization_name' => $x->children()->FIRST_NAME,
                'list_type' => $list_type,
                'comment' => $x->children()->COMMENTS1,
                'address' => $address,
                'alias' => $aliases,
                'others' => $others,
            ]);
            $suspiciousOrganizations->save();
        }
    }
}
