from citations_count_retriever_base import CitationsCountRetrieverBase
from third_party.scholar import ScholarQuerier, ScholarSettings, SearchScholarQuery, ScholarConf
from util import is_same_title


class QueryManipulator:
    def __init__(self, title, articles):
        self.title = title
        self.articles = articles

    def find_best_matching_article(self):
        num_citations = None
        best_matching_article = None
        for art in self.articles:
            if is_same_title(self.title, art.attrs['title'][0]):
                new_num_citations = art.attrs['num_citations'][0]
                if num_citations is None or new_num_citations > num_citations:
                    num_citations = new_num_citations
                    best_matching_article = art

        return best_matching_article

    def generate_report(self):
        best_matching_article = self.find_best_matching_article()

        articles_list = []
        for article in self.articles:
            entry = {'title': article.attrs['title'][0], 'num_citations': article.attrs['num_citations'][0]}
            articles_list.append(entry)

        output = {'best_match_title': best_matching_article.attrs['title'][0] if best_matching_article else None,
                  'best_match_num_citations': best_matching_article.attrs['num_citations'][0] if best_matching_article else None,
                  'articles_list': articles_list,
                  }
        return output

    def get_num_citations(self):
        best_matching_article = QueryManipulator(self.title, self.articles).find_best_matching_article()

        if not best_matching_article:
            num_citations = "Work not found"
        else:
            num_citations = best_matching_article.attrs['num_citations'][0]

        return num_citations


class GoogleScholarCitationsCountRetriever(CitationsCountRetrieverBase):
    @staticmethod
    def __retrieve_articles(author_first_name, author_last_name, title):
        # ignoring first name for now

        querier = ScholarQuerier()
        settings = ScholarSettings()
        querier.apply_settings(settings)
        query = SearchScholarQuery()
        query.set_num_page_results(min(10, ScholarConf.MAX_PAGE_RESULTS))
        query.set_author("%s" % (author_last_name))
        query.set_words(title)
        querier.send_query(query)
        return querier.articles

    def get_num_citations(self, author_first_name, author_last_name, title):
        articles = self.__retrieve_articles(author_first_name, author_last_name, title)
        return QueryManipulator(title, articles).get_num_citations()

    def get_google_scholar_report(self, author_first_name, author_last_name, title):
        articles = self.__retrieve_articles(author_first_name, author_last_name, title)
        return QueryManipulator(title, articles).generate_report()