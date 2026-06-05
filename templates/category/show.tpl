{extends file="layout.tpl"}

{block name=title}{$category.name|escape} — Блог{/block}

{block name=content}
    <p class="back-link">
        <a href="/">&larr; На главную</a>
    </p>

    <header class="category-page__header">
        <h1 class="category-page__title">{$category.name|escape}</h1>
        {if $category.description}
            <p class="category-page__description">{$category.description|escape}</p>
        {/if}
    </header>

    <div class="category-toolbar">
        <span class="category-toolbar__label">Сортировка:</span>
        <a
            href="{$sort_date_url|escape:'html'}"
            class="category-toolbar__link{if $sort == 'date'} is-active{/if}"
        >
            По дате публикации
            {if $sort == 'date'}
                <span class="category-toolbar__direction">{if $order == 'desc'}↓{else}↑{/if}</span>
            {/if}
        </a>
        <a
            href="{$sort_views_url|escape:'html'}"
            class="category-toolbar__link{if $sort == 'views'} is-active{/if}"
        >
            По просмотрам
            {if $sort == 'views'}
                <span class="category-toolbar__direction">{if $order == 'desc'}↓{else}↑{/if}</span>
            {/if}
        </a>
    </div>

    {if $articles|@count == 0}
        <p class="empty-state">В этой категории пока нет статей.</p>
    {else}
        <ul class="article-list">
            {foreach $articles as $article}
                <li class="article-card">
                    {if $article.image}
                        <a href="/article/{$article.slug|escape:'url'}" class="article-card__image-link">
                            <img
                                src="{$article.image|escape}"
                                alt="{$article.title|escape}"
                                class="article-card__image"
                            >
                        </a>
                    {/if}

                    <div class="article-card__body">
                        <h2 class="article-card__title">
                            <a href="/article/{$article.slug|escape:'url'}">
                                {$article.title|escape}
                            </a>
                        </h2>

                        {if $article.description}
                            <p class="article-card__description">{$article.description|escape}</p>
                        {/if}

                        <p class="article-card__meta">
                            {if $article.published_at}
                                <time datetime="{$article.published_at|escape}">
                                    {$article.published_at|date_format:"%d.%m.%Y"}
                                </time>
                            {/if}
                            <span class="article-card__views">{$article.views|escape} просмотров</span>
                        </p>
                    </div>
                </li>
            {/foreach}
        </ul>

        {if $pagination.total_pages > 1}
            <nav class="pagination" aria-label="Навигация по страницам">
                {if $pagination.has_prev}
                    <a
                        href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;order={$order|escape:'url'}&amp;page={$pagination.prev_page}"
                        class="pagination__link"
                    >Назад</a>
                {/if}

                <span class="pagination__info">
                    Страница {$pagination.page|escape} из {$pagination.total_pages|escape}
                </span>

                {if $pagination.has_next}
                    <a
                        href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;order={$order|escape:'url'}&amp;page={$pagination.next_page}"
                        class="pagination__link"
                    >Вперёд</a>
                {/if}
            </nav>
        {/if}
    {/if}
{/block}
