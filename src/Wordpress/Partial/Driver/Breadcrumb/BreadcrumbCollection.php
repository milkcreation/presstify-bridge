<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Breadcrumb;

use tiFy\Contracts\Partial\{Breadcrumb, BreadcrumbCollection as BaseBreadcrumbCollectionContract};
use tiFy\Wordpress\Contracts\Partial\BreadcrumbCollection as BreadcrumbCollectionContract;
use tiFy\Partial\Driver\Breadcrumb\BreadcrumbCollection as BaseBreadcrumbCollection;
use tiFy\Support\Proxy\Url;
use WP_Term;

class BreadcrumbCollection extends BaseBreadcrumbCollection implements BreadcrumbCollectionContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @param Breadcrumb $manager Instance du pilote de fil d'ariane.
     */
    public function __construct(Breadcrumb $manager)
    {
        parent::__construct($manager);

        events()->listen('partial.breadcrumb.fetch', function (BaseBreadcrumbCollectionContract $bc) {
            if (!$this->all()) {
                $this->addRoot(null, true);

                if (is_embed()) {
                    /** @todo */
                } elseif (is_404()) {
                    $this->add404();
                } elseif (is_search()) {
                    $this->addSearch();
                } elseif (is_front_page()) {
                } elseif (is_home()) {
                    if ($id = (int)get_option('page_for_posts')) {
                        if ($acs = $this->getPostAncestorsRender($id)) {
                            array_walk($acs, function ($render) {
                                $this->add($render);
                            });
                        }
                        $this->addHome();
                    }
                } elseif (is_privacy_policy()) {
                    /** @todo */
                } elseif (is_post_type_archive()) {
                    /** @todo */
                } elseif (is_tax()) {
                    if ($acsr = $this->getTermAncestorsRender(get_queried_object_id())) {
                        array_walk($acsr, function ($render) {
                            $this->add($render);
                        });
                    }

                    $this->addTax();
                } elseif (is_attachment()) {
                    /** @todo */
                } elseif (is_single()) {
                    if (get_post_type() === 'post') {
                        if (($id = (int)get_option('page_for_posts')) && ($pr = $this->getPostRender($id))) {
                            $this->add($pr);
                        }
                    } else {
                        if ($acs = $this->getPostAncestorsRender(get_the_ID())) {
                            array_walk($acs, function ($render) {
                                $this->add($render);
                            });
                        }
                    }

                    if ($pr = $this->getPostRender(get_the_ID(), false)) {
                        $this->add($pr);
                    }
                } elseif (is_page()) {
                    if ($acsr = $this->getPostAncestorsRender(get_the_ID())) {
                        array_walk($acsr, function ($render) {
                            $this->add($render);
                        });
                    }

                    if ($pr = $this->getPostRender(get_the_ID(), false)) {
                        $this->add($pr);
                    }
                } elseif (is_singular()) {
                    if ($acsr = $this->getPostAncestorsRender(get_the_ID())) {
                        array_walk($acsr, function ($render) {
                            $this->add($render);
                        });
                    }

                    if ($pr = $this->getPostRender(get_the_ID(), false)) {
                        $this->add($pr);
                    }
                } elseif (is_category()) {
                    $cat_id = get_queried_object_id();

                    if ($acsr = $this->getTermAncestorsRender($cat_id)) {
                        array_walk($acsr, function ($render) {
                            $this->add($render);
                        });
                    }

                    $this->addTax();
                } elseif (is_tag()) {
                    $this->addTax();
                } elseif (is_author()) {
                    /** @todo */
                } elseif (is_date()) {
                    if (is_day()) {
                        $this->add(sprintf(__('Archives du jour : %s', 'tify'),
                            $this->getRender(get_the_date()))
                        );
                    } elseif (is_month()) {
                        $this->add(sprintf(__('Archives du mois : %s', 'tify'),
                            $this->getRender(get_the_date('F Y')))
                        );
                    } elseif (is_year()) {
                        $this->add(sprintf(__('Archives de l\'ann??e : %s', 'tify'),
                            $this->getRender(get_the_date('Y')))
                        );
                    }
                } elseif (is_archive()) {
                    /** @todo */
                }
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function add404(?string $c = null, $u = false, array $a = [], ?int $p = null, array $w = []): int
    {
        $c = $c ? : __('Erreur 404 - Page introuvable', 'tify');
        $u = $this->getUrl($u, (string)Url::current());

        return $this->add($this->getRender($c, $u, $a), $p, $w);
    }

    /**
     * @inheritDoc
     */
    public function addHome(?string $c = null, $u = false, array $a = [], ?int $p = null, array $w = []): int
    {
        $c = $c ? : __('Actualit??s', 'tify');
        $u = $this->getUrl($u, get_post_type_archive_link('post'));

        return $this->add($this->getRender($c, $u, $a), $p, $w);
    }

    /**
     * @inheritDoc
     */
    public function addSearch(?string $c = null, $u = false, array $a = [], ?int $p = null, array $w = []): int
    {
        $c = $c ? : sprintf(__('R??sultats de recherche pour : "%s"', 'tify'), get_search_query());
        $u = $this->getUrl($u, (string)Url::current());

        return $this->add($this->getRender($c, $u, $a), $p, $w);
    }

    /**
     * @inheritDoc
     */
    public function addRoot(?string $c = null, $u = false, array $a = [], ?int $p = null, array $w = []): int
    {
        $c = $c ? : __('Accueil', 'tify');
        $u = $this->getUrl($u, (string)Url::root());
        $a = $u ? array_merge([
            'title' => ($id = get_option('page_on_front'))
                ? sprintf(__('Revenir ?? %s', 'tify'), $this->getPostTitle($id))
                : sprintf(__('Revenir ?? l\'accueil du site %s', 'tify'), get_bloginfo('name')),
        ], $a) : $a;

        return $this->add($this->getRender($c, $u, $a), $p, $w);
    }

    /**
     * @inheritDoc
     */
    public function addTax(?string $c = null, $u = false, array $a = [], ?int $p = null, array $w = []): int
    {
        /** @var WP_Term $term */
        $term = get_queried_object();

        $c = sprintf($c ? : __('%s : %s', 'tify'), get_taxonomy($term->taxonomy)->label, $term->name);
        $u = $this->getUrl($u, (string)get_term_link($term));

        return $this->add($this->getRender($c, $u, $a), $p, $w);
    }

    /**
     * @inheritDoc
     */
    public function getPostAncestorsRender(int $id, bool $url = true, array $attrs = []): array
    {
        $parents = get_post_ancestors($id);

        $items = [];
        foreach (array_reverse($parents) as $post) {
            $items[] = $this->getRender($this->getPostTitle($post), $url ? get_permalink($post): null, $attrs);
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function getPostRender(int $id, bool $url = true, array $attrs = []): string
    {
        return $this->getRender($this->getPostTitle($id), $url ? get_permalink($id): null, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function getPostTitle($post): string
    {
        return esc_html(wp_strip_all_tags(get_the_title(get_post($post)->ID)));
    }

    /**
     * @inheritDoc
     */
    public function getTermAncestorsRender(int $id, bool $url = true, array $attrs = []): array
    {
        if (
            ($term = get_term($id)) instanceof WP_Term &&
            $parents = get_ancestors($term->term_id, $term->taxonomy, 'taxonomy')
        ) {
            $items = [];
            foreach (array_reverse($parents) as $pid) {
                if (($t = get_term($pid)) instanceof WP_Term) {
                    $items[] = $this->getRender($t->name, $url ? get_term_link($t) : null, $attrs);
                }
            }

            return $items;
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getTermRender(int $id, bool $url = true, array $attrs = []): string
    {
        return (($t = get_term($id)) instanceof WP_Term) ? $this->getRender($t->name, $url ? get_term_link($t) : null, $attrs) : '';
    }

    /**
     * R??cup??ration de l'??l??ment lors de l'affichage d'une page liste de contenus relatifs ?? une cat??gorie
     *
     * @return array

    /**
     * R??cup??ration de l'??l??ment lors de l'affichage d'une page liste de contenus relatifs ?? un auteur
     *
     * @return array

    public function currentAuthor()
    {
        $name = get_the_author_meta('display_name', get_query_var('author'));

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass(),
                    ],
                    'content' => sprintf('Auteur : %s', $name),
                ]
            ),
        ];

        return $part;
    } */

    /**
     * R??cup??ration de l'??l??ment lors de l'affichage d'une page liste de contenus relatifs ?? une date
     *
     * @return array

    public function currentDate()
    {
        if (is_day()) :
            $content = sprintf(__('Archives du jour : %s', 'tify'), get_the_date());
        elseif (is_month()) :
            $content = sprintf(__('Archives du mois : %s', 'tify'), get_the_date('F Y'));
        elseif (is_year()) :
            $content = sprintf(__('Archives de l\'ann??e : %s', 'tify'), get_the_date('Y'));;
        endif;

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass(),
                    ],
                    'content' => $content,
                ]
            ),
        ];

        return $part;
    } */

    /**
     * R??cup??ration de l'??l??ment lors de l'affichage d'une page liste de contenus
     *
     * @return array

    public function currentArchive()
    {
        $content = (is_post_type_archive())
            ? post_type_archive_title('', false)
            : __('Actualit??s', 'tify');

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass(),
                    ],
                    'content' => $content,
                ]
            ),
        ];

        return $part;
    } */

    /**
     * @return \tiFy\Partial\Driver\Tag\Tag
     * @todo Suppression des redondances current pr??c??dentes
     *

    protected function partCurrent($attrs)
    {

    }  */

    /**
     * @return \tiFy\Partial\Driver\Tag\Tag
     * @todo Suppression des redondances link pr??c??dentes
     *

    protected function partLink($attrs)
    {

    } */

    /**
     * R??cup??ration des anc??tres selon le contexte
     *
     * @return void

    protected function getAncestorsPartList()
    {
        if (is_attachment()) :
            if ($parents = \get_ancestors(get_the_ID(), get_post_type())) :
                if (('post' === get_post_type(reset($parents))) && ($page_for_posts = get_option('page_for_posts'))) :
                    $title = $this->getPostTitle($page_for_posts);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => get_permalink($page_for_posts),
                                    'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => $title,
                            ]
                        ),
                    ];
                endif;

                reset($parents);

                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => get_permalink($parent),
                                    'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => $title,
                            ]
                        ),
                    ];
                endforeach;
            endif;
        elseif (is_home() && is_paged()) :
            if ($page_for_posts = get_option('page_for_posts')) :
                $title = $this->getPostTitle($page_for_posts);

                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => get_permalink($page_for_posts),
                                'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                'class' => $this->getItemContentClass(),
                            ],
                            'content' => $title,
                        ]
                    ),
                ];
            else :
                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => home_url('/'),
                                'title' => __('Revenir ?? la liste des actualit??s', 'tify'),
                                'class' => $this->getItemContentClass(),
                            ],
                            'content' => __('Actualit??s', 'tify'),
                        ]
                    ),
                ];
            endif;
        elseif (is_single()) :
            // Le type du contenu est un article de blog
            if (is_singular('post')) :
                if ($page_for_posts = get_option('page_for_posts')) :
                    $title = $this->getPostTitle($page_for_posts);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => get_permalink($page_for_posts),
                                    'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => $title,
                            ]
                        ),
                    ];
                else :
                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => home_url('/'),
                                    'title' => __('Revenir ?? la liste des actualit??s', 'tify'),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => __('Actualit??s', 'tify'),
                            ]
                        ),
                    ];
                endif;

            // Le type de contenu autorise les pages d'archives
            elseif (($post_type_obj = get_post_type_object(get_post_type())) && $post_type_obj->has_archive) :
                $title = $post_type_obj->labels->name;

                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => get_post_type_archive_link(\get_post_type()),
                                'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                'class' => $this->getItemContentClass(),
                            ],
                            'content' => $title,
                        ]
                    ),
                ];
            endif;

            // Le contenu a des anc??tres
            if ($parents = get_ancestors(get_the_ID(), get_post_type())) :
                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => get_permalink($parent),
                                    'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => $title,
                            ]
                        ),
                    ];
                endforeach;
            endif;

        elseif (is_page()) :
            if ($parents = get_ancestors(get_the_ID(), get_post_type())) :
                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => get_permalink($parent),
                                    'title' => sprintf(__('Revenir ?? %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass(),
                                ],
                                'content' => $title,
                            ]
                        ),
                    ];
                endforeach;
            endif;
        endif;
    } */
}