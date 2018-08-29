from jstor_citations_count_retriever import JstorCitationsCountRetriever
from google_scholar_citations_count_retriever import GoogleScholarCitationsCountRetriever
import sys


def main():

    first = sys.argv[1]
    last = sys.argv[2]
    titles = sys.argv[3].split('|')
    year = sys.argv[4]

    title = titles[0]  # First title is considered canonical title. All others are alternates.


    jstor_citations = JstorCitationsCountRetriever().get_num_citations(
        author_first_name=first, author_last_name=last, titles=titles)
    print("JSTOR: %s." % jstor_citations)

    google_scholar_citations = GoogleScholarCitationsCountRetriever().get_google_scholar_report(
        author_first_name=first, author_last_name=last, title=title)
    if google_scholar_citations['best_match_num_citations']:
        print("Google Scholar: %s." % google_scholar_citations['best_match_num_citations'])
        print("Google Scholar best match title: %s" % google_scholar_citations['best_match_title'])
    else:
        print("Couldn't find Google Scholar article with matching title.")
    print("")
    print("All google scholar articles:")
    print("")
    for article in google_scholar_citations['articles_list']:
        print("Title: %s" % article['title'])
        print("Num citations: %s" % article['num_citations'])

    yes_or_no = input("\n"
                      "Would you like to insert the following into the database? (y/n)\n"
                      "%s %s '%s' %s" % (first, last, title, year))

    if yes_or_no == 'y':
        print('Inserting record...')


if __name__ == "__main__":
    main()
