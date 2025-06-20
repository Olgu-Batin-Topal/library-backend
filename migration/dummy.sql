INSERT INTO authors (id, name, email) VALUES (1, 'Ahmet Yılmaz', 'ahmet.yilmaz@example.com');
INSERT INTO authors (id, name, email) VALUES (2, 'Elif Demir', 'elif.demir@example.com');
INSERT INTO authors (id, name, email) VALUES (3, 'Murat Kaya', 'murat.kaya@example.com');

INSERT INTO categories (id, name, description) VALUES (1, 'Bilim', '');
INSERT INTO categories (id, name, description) VALUES (2, 'Kurgu', '');
INSERT INTO categories (id, name, description) VALUES (3, 'Tarih', '');
INSERT INTO categories (id, name, description) VALUES (4, 'Psikoloji', '');
INSERT INTO categories (id, name, description) VALUES (5, 'Sanat', '');

INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (1, 1, 'Yalnızlık Peşinde Bir Gün', '9783894012631', 2001, 561);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (1, 2, 'Zaman Üzerine Gerçekler', '9792488622214', 2016, 491);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (1, 3, 'Gece ve Hatıralar', '9799589915386', 2020, 271);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (1, 4, 'Rüya ve Bir Gün', '9787327893900', 1993, 626);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (1, 5, 'Yalnızlık İzinde Hatıralar', '9787257203628', 2002, 454);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (2, 1, 'Hayat ve Anılar', '9792980209810', 2013, 420);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (2, 2, 'Umut Arasında Bir Hikaye', '9787175817414', 2014, 643);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (2, 3, 'Zaman Başlangıcı Hatıralar', '9784215919625', 2003, 153);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (2, 4, 'Düşler Üzerine Gerçekler', '9781529642365', 2019, 298);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (2, 5, 'Hayat ve Bir Gün', '9784271701217', 2024, 573);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (3, 1, 'Sessizlik Arasında Bir Masal', '9787639503371', 2005, 159);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (3, 2, 'Rüya ve Bir Masal', '9791837319932', 2000, 503);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (3, 3, 'Yalnızlık İzinde Bir Hikaye', '9794224794169', 1991, 621);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (3, 4, 'Hayat Başlangıcı Gerçekler', '9783639672477', 2010, 117);
INSERT INTO books (author_id, category_id, title, isbn, publication_year, page_count) VALUES (3, 5, 'Karanlık Peşinde Hatıralar', '9797238222058', 2017, 482);

