create table author
(
    id         int unsigned auto_increment
        primary key,
    first_name varchar(50) not null,
    last_name  varchar(50) not null
);

create table book
(
    id        int unsigned auto_increment
        primary key,
    name      varchar(50)                                                                                              not null,
    isbn10    char(13)                                                                                                 not null,
    text      text                                                                                                     null,
    author_id int unsigned                                                                                             not null,
    genres    set ('Adventure', 'Science fiction', 'Thriller', 'Horror', 'Magical realism', 'Romance', 'Dark fantasy') null,
    type      set ('Novel', 'Novelette') default 'Novel'                                                               null,
    synopsis  varchar(200)                                                                                             null,
    constraint book_isbn
        unique (isbn10),
    constraint book_ibfk_1
        foreign key (author_id) references author (id)
);

create index author_id
    on book (author_id);

create table publisher
(
    id      int unsigned auto_increment
        primary key,
    name    varchar(200) not null,
    address varchar(500) not null
);

create table author_publisher
(
    author_id    int unsigned not null,
    publisher_id int unsigned not null,
    constraint author_id
        unique (author_id, publisher_id),
    constraint author_publisher_ibfk_1
        foreign key (author_id) references author (id),
    constraint author_publisher_ibfk_2
        foreign key (publisher_id) references publisher (id)
);

create index publisher_id
    on author_publisher (publisher_id);

create table edition
(
    id                 int unsigned auto_increment
        primary key,
    book_id            int unsigned                                 not null,
    publisher_id       int unsigned                                 not null,
    price              int unsigned       default '0'               not null,
    author_reward_base int unsigned       default '0'               not null,
    author_reward_copy int unsigned       default '0'               not null,
    sold_copies_count  mediumint unsigned default '0'               null,
    status             varchar(15)                                  null,
    published_at       timestamp                                    null,
    created_at         timestamp          default CURRENT_TIMESTAMP not null,
    updated_at         timestamp          default CURRENT_TIMESTAMP not null,
    constraint edition_book_id_fk
        foreign key (book_id) references book (id),
    constraint edition_publisher_id_fk
        foreign key (publisher_id) references publisher (id)
);

