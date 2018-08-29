from google_scholar_citations_count_retriever import QueryManipulator
from unittest import TestCase
from third_party.scholar import ScholarArticle


class GoogleScholarCitationsCountRetrieverTest(TestCase):

    def setUp(self):
        self.articles = [self.__create_scholar_article('Made up book, volume 1', 100),
                    self.__create_scholar_article('Another made up book.', 124),
                    self.__create_scholar_article('Made up book: vol I', 101),
                    self.__create_scholar_article('This is a different book', 23)]

        self.expected_report = {'best_match_title': 'Made up book: vol I',
                                'best_match_num_citations': 101,
                                'articles_list': [
                                    {
                                        'title': 'Made up book, volume 1',
                                        'num_citations': 100,
                                    },
                                    {
                                        'title': 'Another made up book.',
                                        'num_citations': 124,
                                    },
                                    {
                                        'title': 'Made up book: vol I',
                                        'num_citations': 101,
                                    },
                                    {
                                        'title': 'This is a different book',
                                        'num_citations': 23,
                                    }
                                ]}

    @staticmethod
    def __create_scholar_article(title, citations):
        article = ScholarArticle()
        article.attrs['title'][0] = title
        article.attrs['num_citations'][0] = citations
        return article

    def test_find_best_matching_article(self):
        query_manipulator = QueryManipulator('Made up book, volume 1', self.articles)
        best_match = query_manipulator.find_best_matching_article()
        self.assertEqual(best_match.attrs['title'][0], 'Made up book: vol I')

        query_manipulator = QueryManipulator('This is a different book.', self.articles)
        best_match = query_manipulator.find_best_matching_article()
        self.assertEqual(best_match.attrs['title'][0], 'This is a different book')

    def test_generate_report(self):
        query_manipulator = QueryManipulator('Made up book, volume 1', self.articles)
        report = query_manipulator.generate_report()
        self.assertEqual(report, self.expected_report)