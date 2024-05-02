# create database sql_query_fix;
# use sql_query_fix;

# ВИКОНАТИ НАСТУПНИЙ СКРИПТ:

create table if not exists author
(
    id         int unsigned auto_increment
        primary key,
    first_name varchar(50) not null,
    last_name  varchar(50) not null
);

create table if not exists book
(
    id        int unsigned auto_increment
        primary key,
    name      varchar(50)                                                                                              not null,
    isbn10    char(13)                                                                                                 not null,
    text      text                                                                                                     null,
    author_id int unsigned                                                                                             not null,
    genres    set ('Adventure', 'Science fiction', 'Thriller', 'Horror', 'Magical realism', 'Romance', 'Dark Fantasy') null,
    type      set ('Novel', 'Novelette') default 'Novel'                                                               null,
    constraint book_ibfk_1
        foreign key (author_id) references author (id)
);

INSERT IGNORE INTO author (id, first_name, last_name) VALUES (1675, 'Zaria', 'Barton');

INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (3326, 'Est aperiam.', '8830161489', 'Repudiandae odio optio quibusdam sunt et qui nesciunt amet. Unde molestiae porro qui quae temporibus veniam animi at. Magni sint modi dolor dolor sapiente.', 1675, 'Thriller,Romance', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (3327, 'Accusantium quia.', '3679509375', 'Reprehenderit repudiandae voluptates et. Recusandae cupiditate aut sit enim doloremque ullam vel. Alias qui et dolores ut autem qui.', 1675, 'Science fiction,Magical realism', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (8649, 'Tempora dolores et.', '0797121986', 'Exercitationem pariatur fuga vero fugiat non eum. Quod est consequuntur praesentium sequi ut accusamus. Et et dolores odit deserunt necessitatibus optio animi.', 1675, 'Adventure,Science fiction', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (22943, 'Occaecati aut in.', '7482383522', 'Error est ut eaque. Officiis qui quis quia qui accusamus. Quia aut libero similique molestias velit ipsa voluptatem.', 1675, 'Dark Fantasy', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (24583, 'Quidem facilis odit.', '4758194009', 'Non veritatis sit perferendis assumenda. Sunt et sit voluptatem quos ab cumque perspiciatis.', 1675, 'Dark Fantasy', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (25146, 'Voluptates ut.', '2154230628', 'Dolorem voluptatem earum at debitis. Quam ab et eaque est. Fugiat recusandae nisi explicabo omnis.', 1675, 'Dark Fantasy', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (25310, 'Et iusto facere vel.', '9573912252', 'Quasi nulla animi illo velit quo nihil. Eveniet et id aliquid sint similique hic. Ut nihil corporis vitae quae in et. Omnis fuga voluptatem commodi quis magni molestias.', 1675, 'Dark Fantasy', 'Novel');
INSERT IGNORE INTO book (id, name, isbn10, text, author_id, genres, type) VALUES (27888, 'Magni et ut.', '1667543172', 'Quae eos repellendus fugiat quia et itaque possimus. Et asperiores nihil quis sunt. Eveniet eum voluptatem ullam molestiae id nam et. Rerum est atque quia tempore deleniti aliquid hic praesentium.', 1675, null, 'Novel');


# ОПИС: Запит підраховує кількість книг для автора  "Zaria Barton"
#
# ЗАВДАННЯ:
# 1. Виправити помилку в запиті, бо зараз кількість книг невірна
# 2. Оптимізувати запит через використання індексів, позбутись підзапиту.
#
# НА ВИХОДІ має бути новий SQL-файл зі змінами до таблиці (за потреби) та оптимізованим запитом,
# що видає правильну кількість книг для цього автора.
#
select author.id, first_name, last_name, count(author.id) as book_count
from author
where author.id in
      (select author_id
       from book
       where author.first_name = 'Zaria'
         and author.last_name = 'Barton')
group by author.id;
