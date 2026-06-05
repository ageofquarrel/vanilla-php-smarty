{extends file="layout.tpl"}

{block name=title}{$article.title|escape} — Блог{/block}

{block name=content}
    <p class="back-link">
        <a href="/">&larr; На главную</a>
    </p>

    <article class="article-page">
        {if $article.image}
            <img
                src="{$article.image|escape}"
                alt="{$article.title|escape}"
                class="article-page__image"
            >
        {/if}

        <header class="article-page__header">
            <h1 class="article-page__title">{$article.title|escape}</h1>

            <p class="article-page__meta">
                {if $article.published_at}
                    <time datetime="{$article.published_at|escape}">
                        {$article.published_at|date_format:"%d.%m.%Y"}
                    </time>
                {/if}
                <span class="article-page__views">{$article.views|escape} просмотров</span>
            </p>

            {if $categories|@count > 0}
                <ul class="article-page__categories">
                    {foreach $categories as $category}
                        <li>
                            <a href="/category/{$category.slug|escape:'url'}">{$category.name|escape}</a>
                        </li>
                    {/foreach}
                </ul>
            {/if}
        </header>

        {if $article.description}
            <p class="article-page__lead">{$article.description|escape}</p>
        {/if}

        <div class="article-page__body">
            {$article.body|escape|nl2br}
        </div>
    </article>

    {if $similar|@count > 0}
        <section class="similar-posts">
            <h2 class="similar-posts__title">Похожие статьи</h2>

            <ul class="article-list">
                {foreach $similar as $item}
                    <li class="article-card">
                        {if $item.image}
                            <a href="/article/{$item.slug|escape:'url'}" class="article-card__image-link">
                                <img
                                    src="{$item.image|escape}"
                                    alt="{$item.title|escape}"
                                    class="article-card__image"
                                >
                            </a>
                        {/if}

                        <div class="article-card__body">
                            <h3 class="article-card__title">
                                <a href="/article/{$item.slug|escape:'url'}">
                                    {$item.title|escape}
                                </a>
                            </h3>

                            {if $item.description}
                                <p class="article-card__description">{$item.description|escape}</p>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </section>
    {/if}
{/block}
