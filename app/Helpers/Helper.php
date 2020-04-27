<?php

use Illuminate\Support\Str;

function wordcombos($words): array
{
    if (count($words) <= 1) {
        $result = $words;
    } else {
        $result = array();
        for ($i = 0; $i < count($words); ++$i) {
            $firstword = $words[$i];
            $remainingwords = array();
            for ($j = 0; $j < count($words); ++$j) {
                if ($i <> $j) {
                    $remainingwords[] = $words[$j];
                }
            }
            $combos = wordcombos($remainingwords);
            for ($j = 0; $j < count($combos); ++$j) {
                $result[] = $firstword . $combos[$j];
            }
        }
    }

    return $result;
}

function convertLetters($value)
{
    $cyr = [
        'й',
        'ч',
        'ғ',
        'ё',
        'ж',
        'х',
        'ш',
        'ю',
        'я',
        'Й',
        'Ч',
        'Ғ',
        'Ё',
        'Ж',
        'Х',
        'Ш',
        'Ю',
        'Я',
        'а',
        'б',
        'в',
        'г',
        'д',
        'е',
        'з',
        'и',
        'к',
        'қ',
        'л',
        'м',
        'н',
        'о',
        'п',
        'р',
        'с',
        'т',
        'у',
        'ф',
        'ҳ',
        'ҷ',
        'ъ',
        'ь',
        'э',
        'А',
        'Б',
        'В',
        'Г',
        'Д',
        'Е',
        'З',
        'И',
        'К',
        'Қ',
        'Л',
        'М',
        'Н',
        'О',
        'П',
        'Р',
        'С',
        'Т',
        'У',
        'Ф',
        'Ҳ',
        'Ҷ',
        'Ъ',
        'Ь',
        'Э'
    ];
    $lat = [
        'y',
        'ch',
        'gh',
        'yo',
        'zh',
        'kh',
        'sh',
        'yu',
        'ya',
        'Y',
        'CH',
        'GH',
        'YO',
        'ZH',
        'KH',
        'SH',
        'YU',
        'YA',
        'a',
        'b',
        'v',
        'g',
        'd',
        'e',
        'z',
        'i',
        'k',
        'q',
        'l',
        'm',
        'n',
        'o',
        'p',
        'r',
        's',
        't',
        'u',
        'f',
        'h',
        'j',
        '',
        '',
        'e',
        'A',
        'B',
        'V',
        'G',
        'D',
        'E',
        'Z',
        'I',
        'K',
        'Q',
        'L',
        'M',
        'N',
        'O',
        'P',
        'R',
        'S',
        'T',
        'U',
        'F',
        'H',
        'J',
        '',
        '',
        'E'
    ];

    if (preg_match('/[a-zA-Z]/', $value)) {
        return str_replace($lat, $cyr, $value);
    }

    if (!preg_match('/[a-zA-Z]/', $value)) {
        return str_replace($cyr, $lat, $value);
    }

    return $value;
}


function makeListOfWordCombinations(string $initials)
{
    $arrayOfInitials = makeArrayWithoutNulls($initials);

    $convertedLetters = convertLetters(implode(" ", $arrayOfInitials));

    $arrayOfConvertedInitials = explode(" ", $convertedLetters);

    $wordCombinations = wordcombos($arrayOfInitials);

    $convertedWordCombinations = wordcombos($arrayOfConvertedInitials);

    return array_merge($convertedWordCombinations, $wordCombinations);
}


function getInitinals($subject): string
{
    $initials = (isRabishDataExists($subject->second_name) ? preg_replace('/\s+/', '', $subject->second_name) . ' ' : '')
        . (isRabishDataExists($subject->first_name) ? preg_replace('/\s+/', '', $subject->first_name) . ' ' : '')
        . (isRabishDataExists($subject->third_name) ? preg_replace('/\s+/', '', $subject->third_name) . ' ' : '')
        . (isRabishDataExists($subject->fourth_name) ? preg_replace('/\s+/', '', $subject->fourth_name) . ' ' : '');

    return Str::lower(trim($initials));
}

function concatinateInitials($secondName, $firstName, $thirdName, $fourthName): string
{
    $initials = (isRabishDataExists($secondName) ? preg_replace('/\s+/', '', $secondName) : '')
        . (isRabishDataExists($firstName) ? preg_replace('/\s+/', '', $firstName) : '')
        . (isRabishDataExists($thirdName) ? preg_replace('/\s+/', '', $thirdName) : '')
        . (isRabishDataExists($fourthName) ? preg_replace('/\s+/', '', $fourthName) : '');

    return Str::lower(trim($initials));
}

function isRabishDataExists($data): bool
{
    $result = true;

    switch ($data) {
        case strlen($data) > 22: $result =false; break;
        case $data == 'null': $result =false; break;
        case $data == 'UnknownSURNAME': $result =false; break;
        case $data == 'UnknownNAME': $result =false; break;
        case $data == 'NULL': $result =false; break;
        case $data == 'нет': $result =false; break;
        case $data == 'НЕТ':$result =false; break;
        case preg_match("/[(|)]/", $data) === 1: $result = false; break;
    }
    return $result;
}

function makeArrayWithoutNulls(string $initials)
{
    $arrayOfInitials = explode(" ", $initials);

    for ($i = 0; $i < count($arrayOfInitials); $i++) {
        $arrayOfInitials[$i] = cleanLists($arrayOfInitials[$i]);

    }

    return $arrayOfInitials;
}

function cleanLists($word)
{
    //repeate check for rubish
    $word = (str_replace('null', '', $word));
    $word = (str_replace('UnknownSURNAME', '', $word));
    $word = (str_replace('UnknownNAME', '', $word));
    $word = (str_replace('NULL', '', $word));
    $word = (str_replace('нет', '', $word));


    if (preg_match('/[a-zA-Z]/', $word)) {
        $word = (preg_replace('/[^A-Za-z]/', '', $word));
    }

    if (preg_match('/[А-Яа-я]/', $word)) {
        $word = mb_ereg_replace('[^А-Яа-я]', '', $word);
    }

    return $word;
}

function makeMessage($subject, $operationType, $requestedInitials): string
{
    $message =
        '- Ф.И.О: ' . $subject->second_name
        . ' ' . $subject->first_name
        . ' ' . $subject->third_name ."\n"
        . '- Организация: ' . $subject->organization."\n"
        . '- Процент совпадения:' . round(((float)$subject->sim) * 100) . '%.'."\n"
    .' - Тип Операции: '. $operationType."\n"
        .'- (Запрошен: '.$requestedInitials.').';

    return (string)$message;
}
