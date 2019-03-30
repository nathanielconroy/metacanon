import urllib
import random
import requests
import bs4
import re
import string

from citations_count_retriever_base import CitationsCountRetrieverBase


class JstorCitationsCountRetriever(CitationsCountRetrieverBase):
    def get_num_citations(self, author_first_name, author_last_name, titles):
        url = self.__generate_url(author_first_name, author_last_name, titles)
        headers = {'user-agent': self.__get_random_string(20), 'disallow': '/'}
        raw_html = requests.get(url, headers=headers)

        soup = bs4.BeautifulSoup(raw_html.text, "html.parser")
        if soup.find("html") is None:
            total_results = 'URL Error\n'
            print("Couldn't parse html file")
            print(soup)
        elif soup.find("h1") is None:
            total_results = '0'
            print("Couldn't find header in html file")
            print(raw_html.text)
        else:
            total_results = soup.find("h2", {"data-result-count": True})["data-result-count"]
            print(total_results)

        try:
            total_results = int(re.sub('[^0-9]', '', total_results))
        except ValueError:
            total_results = 0
        return total_results

    @staticmethod
    def __generate_url(author_first_name, author_last_name, titles):
        titles_term = ''
        for i in range(0,len(titles)):
            titles_term += '"%s"' % titles[i]
            if i != len(titles) - 1:
                titles_term += ' OR '

        search_term = '(("%s %s") OR (%s, %s)) AND (%s)' % (author_first_name, author_last_name,
                                                            author_last_name, author_first_name, titles_term)
        url = "https://www.jstor.org/action/doBasicSearch?Query=" + urllib.parse.quote(search_term, safe='') + \
              "&acc=off&wc=on&fc=off&group=none"
        print("Sending request to %s" % url)
        return url

    @staticmethod
    def __get_random_string(length):
        random_string = "something"
        for i in range(0, length):
            random_string += random.choice(string.ascii_lowercase)
        return random_string

