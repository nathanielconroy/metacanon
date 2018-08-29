import string
import roman


def _roman_to_arabic(title):
    words = title.split()
    for i in range(0, len(words)):
        try:
            number = roman.fromRoman(words[i].upper())
        except roman.InvalidRomanNumeralError:
            number = None
        if number:
            words[i] = str(number)
    return ' '.join(words)


def _normalize_synonyms(title):
    words = title.split()
    for i in range(0, len(words)):
        if words[i] == 'volume':
            words[i] = 'vol'
    return ' '.join(words)


def _normalize_title(title):
    table = str.maketrans(dict.fromkeys(string.punctuation))
    lower_case_title = title.translate(table).lower()
    no_roman_title = _roman_to_arabic(lower_case_title)
    desynonymed_title = _normalize_synonyms(no_roman_title)
    return desynonymed_title


def is_same_title(searched_title, found_title):
    normalized_search_title = _normalize_title(searched_title)
    normalized_found_title = _normalize_title(found_title)
    return normalized_search_title in normalized_found_title
