# oc-plugin-book


## install

```
git clone https://github.com/jc91715/oc-plugin-book plugins/jc91715/book
```

## migrate

```
php artisan october:up
```

## create lists page and list page and translate page


example:

 *  lists page: `/books` 
 put the `BookLists` component in this page,choose the chapterPage redirect to list page


* list page: `/books/:doc_id/chapters/:chapter_id?`

the url must contain `:doc_id` and `chapter_id` params
put the `BookList` component in this page,choose the translatePage redirect to translate page

* translate page: `/:class_type/:slug/translate/:type?`

put the `Translate` component in this page

create some data in backend 

## visit `yourdomain.com/books`






