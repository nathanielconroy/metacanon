import unittest
from test.util_test import UtilTest
from test.google_scholar_citations_count_retriever_test import GoogleScholarCitationsCountRetrieverTest

all_tests = unittest.TestSuite()
all_tests.addTest(unittest.makeSuite(UtilTest))
all_tests.addTest(unittest.makeSuite(GoogleScholarCitationsCountRetrieverTest))
unittest.main()
