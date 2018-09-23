import pymysql.cursors


def get_config():
    with open('config.txt') as config_file:
        content = config_file.readlines()
    return content[0].rstrip(), content[1].rstrip(), content[2].rstrip()


def insert_work(first, last, title, search_friendly_title, alt_titles, year,
                google_scholar_citations, jstor_citations):

    alt_titles = '|'.join(alt_titles) if alt_titles else ''

    host, user, password = get_config()

    full_name = "%s, %s" % (last, first)
    db = pymysql.connect(host=host,
                         user=user,
                         password=password,
                         db='metacanon',
                         charset='utf8mb4',
                         cursorclass=pymysql.cursors.DictCursor)
    with db.cursor() as cur:
        sql = """INSERT INTO works (author_first, author_last, title, year, google_scholar, jstor, fullname)
                 VALUES (%s, %s, %s, %s, %s, %s, %s)"""
        cur.execute(sql, (first, last, title, year, google_scholar_citations, jstor_citations, full_name))

        sql = """INSERT INTO search_friendly_titles (work_id, search_friendly_title, alternate_titles)
                 VALUES ((SELECT MAX(work_id) FROM works), %s, %s)"""
        cur.execute(sql, (search_friendly_title, alt_titles))

    db.commit()
    db.close()

