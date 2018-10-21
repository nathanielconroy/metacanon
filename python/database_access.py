from config import CONFIG

import pymysql.cursors
import datetime


def insert_work(first, last, title, search_friendly_title, alt_titles, year,
                google_scholar_citations, jstor_citations, format, genre, tags):

    tags = ';'.join(tags)

    last_updated = datetime.datetime.today().date()

    alt_titles = '|'.join(alt_titles) if alt_titles else ''

    full_name = "%s, %s" % (last, first)
    db = pymysql.connect(host=CONFIG['host'],
                         user=CONFIG['user'],
                         password=CONFIG['password'],
                         db=CONFIG['db'],
                         charset='utf8mb4',
                         cursorclass=pymysql.cursors.DictCursor)
    with db.cursor() as cur:
        sql = """INSERT INTO works (author_first, author_last, title, year, google_scholar, jstor, fullname, format, genre, tags, last_updated)
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
        cur.execute(sql, (first, last, title, year, google_scholar_citations, jstor_citations, full_name, format, genre, tags, last_updated))

        sql = """INSERT INTO search_friendly_titles (work_id, search_friendly_title, alternate_titles)
                 VALUES ((SELECT MAX(work_id) FROM works), %s, %s)"""
        cur.execute(sql, (search_friendly_title, alt_titles))

    db.commit()
    db.close()

