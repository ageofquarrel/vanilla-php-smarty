{extends file="layout.tpl"}

{block name=title}Главная — Блог{/block}

{block name=content}
    <h1>Главная</h1>

    {if $articles|@count > 0}
        <section class="recent-posts">
            <h2 class="recent-posts__title">Последние посты</h2>

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
                            <h3 class="article-card__title">
                                <a href="/article/{$article.slug|escape:'url'}">
                                    {$article.title|escape}
                                </a>
                            </h3>

                            {if $article.description}
                                <p class="article-card__description">{$article.description|escape}</p>
                            {/if}

                            {if $article.published_at}
                                <time class="article-card__date" datetime="{$article.published_at|escape}">
                                    {$article.published_at|date_format:"%d.%m.%Y"}
                                </time>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </section>
    {/if}

    {if $categories|@count == 0}
        <p class="empty-state">Пока нет категорий со статьями.</p>
    {else}
        {foreach $categories as $category}
            <section class="category-section">
                <header class="category-section__header">
                    <h2 class="category-section__title">{$category.name|escape}</h2>
                    {if $category.description}
                        <p class="category-section__description">{$category.description|escape}</p>
                    {/if}
                </header>

                <p class="category-section__actions">
                    <a href="/category/{$category.slug|escape:'url'}" class="button">
                        Все статьи
                    </a>
                </p>
            </section>
        {/foreach}
    {/if}
{/block}
