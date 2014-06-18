<?php

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 *
 * @see http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
 */
function str_starts_with($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 *
 * @see http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
 */
function str_ends_with($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

/**
 * @param $search
 * @param $replace
 * @param $subject
 *
 * @return string
 *
 * @see http://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match#answer-2606638
 */
function str_replace_first($search, $replace, $subject) {
    if (empty($search)) {
        return $subject;
    }

    return implode($replace, explode($search, $subject, 2));
}
