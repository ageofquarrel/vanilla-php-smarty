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
                            {if $article.views}
                                <span class="article-card__views">{$article.views|escape} просмотров</span>
                            {/if}
                        </p>
                    </div>
                </li>
            {/foreach}
        </ul>
    {/if}
{/block}
