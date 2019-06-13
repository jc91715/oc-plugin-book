# oc-plugin-book


## install

```
git clone https://github.com/jc91715/oc-plugin-book plugins/jc91715/book
```

## migrate

```
php artisan october:up
```

## create lists page and list page


example:

 *  lists page: `/books` put the `BookLists` component in this page,choose the chapterPage redirect to list page


* list page: `/books/:doc_id/chapters/:chapter_id?`

the url must contain `:doc_id` and `chapter_id` params

## visit `yourdomain.com/books`






