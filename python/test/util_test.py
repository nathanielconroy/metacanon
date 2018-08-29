from unittest import TestCase
from util import *


class UtilTest(TestCase):
    @staticmethod
    def test_is_same_title():
        version_one = "capital vol 1"
        version_two = "Capital, Vol 1."
        assert is_same_title(version_one, version_two)

        version_one = "Capital, Vol 1"
        version_two = "Capital, Vol I"
        assert is_same_title(version_one, version_two)

        version_one = "Capital   : vol Ii"
        version_two = "Capital, Volume 2"
        assert is_same_title(version_one, version_two)

        version_one = "Capital, part II"
        version_two = "Capital, vol II"
        assert not is_same_title(version_one, version_two)
