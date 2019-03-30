from jstor_citations_count_retriever import JstorCitationsCountRetriever
from google_scholar_citations_count_retriever import GoogleScholarCitationsCountRetriever
from database_access import insert_work

import argparse


def main():

    parser = argparse.ArgumentParser(description='Get citations counts.')
    parser.add_argument('--first_name', nargs='?', required=True)
    parser.add_argument('--last_name', nargs='?', required=True)
    parser.add_argument('--title', nargs='?', required=True)
    parser.add_argument('--year', nargs='?', required=True)
    parser.add_argument('--alt_titles', nargs='*')
    parser.add_argument('--tags', nargs='*')
    parser.add_argument('--genre', nargs='?')
    parser.add_argument('--format', nargs='?')
    parser.add_argument('--search_friendly_title', nargs='?')

    args = parser.parse_args()

    first = args.first_name
    last = args.last_name
    title = args.title
    year = args.year
    titles = [args.title]
    titles = titles + args.alt_titles if args.alt_titles else titles
    search_friendly_title = args.search_friendly_title if args.search_friendly_title else title
    alternate_titles = args.alt_titles if args.alt_titles else None
    genre = args.genre if args.genre else 'non_fiction'
    format = args.format if args.format else 'book'
    tags = args.tags if args.tags else []

    jstor_citations = JstorCitationsCountRetriever().get_num_citations(
        author_first_name=first, author_last_name=last, titles=titles)
    print("JSTOR: %s." % jstor_citations)

    google_scholar_citations = GoogleScholarCitationsCountRetriever().get_google_scholar_report(
        author_first_name=first, author_last_name=last, title=title)
    if google_scholar_citations['best_match_num_citations'] is not None:
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
        insert_work(first=first,
                    last=last,
                    title=title,
                    year=year,
                    search_friendly_title=search_friendly_title,
                    alt_titles=alternate_titles,
                    google_scholar_citations=google_scholar_citations['best_match_num_citations'],
                    jstor_citations=jstor_citations,
                    genre=genre,
                    tags=tags,
                    format=format)


if __name__ == "__main__":
    main()
