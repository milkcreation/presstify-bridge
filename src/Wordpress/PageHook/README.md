# PageHook (Page d'accroche)

## A propos

Hook permet d'associer un contenu Wordpress à l'une des nombreuses fonctionnalités offertes par presstiFy mais vous 
pouvez aussi choisir de coupler ce contenu à l'une de vos propres fonctionnalités.

Vous pourrez par exemple associer un contenu Wordpress à un gabarit d'affichage particulier ou décider de l'affichage 
d'un formulaire presstiFy ou associer une route ...

## Activation

Hook est une dépendance de l'adaptateur Wordpress de Presstify. Il n'est pour l'instant pas possible de désactiver 
cette fonctionnalité.

## Configuration

Il y a plusieurs façons de configurer une page d'accroche. Soit de manière semi-dynamique, par le biais du fichier de 
configuration; soit de manière complétement dynamique depuis un controleur.

> Par défaut, le nom de qualification est utilisé en tant que clé de donnée pour l'enregistrement en base, 
veillez donc à utiliser moins de 180 caractères. 

### 1. Depuis le fichier de configuration

Créer un fichier page-hook.php dans le dossier de stockage de configuration de presstiFy.

Vous trouverez un exemple de configuration dans le dossier Resources/config du composant.

```php
<?php 
return [
    'my_hook_name' => [
        /** Attributs de configuration */
    ]
];

```

### 2. Depuis un contrôleur

Soit grâce au contrôleur d'injection de dépendances :

```php
<?php 

namespace App;

class Acme {
    public function __construct(App $app)
    {
        $app->get('wp.page-hook')->set('my_hook_name', [
            /** Attributs de configuration */
        ]);
    }
}

```

Soit grâce à l'accesseur :

```php
<?php 
    $page_hook = page_hook()->set('my_hook_name', [
        /** Attributs de configuration */
    ]);
}

```

## Liste des attributs de configuration (par ordre alphabétique)

### id (facultatif)

@var integer

Définition de l'identifiant de qualification du post Wordpress associé.

### desc (facultatif)

@var string|closure

Description détaillée (non affichée dans la version actuelle).

### display_post_states (facultatif)

@var boolean|string

Activation de l'affichage de l'association d'un post à une page d'accroche dans la liste des posts de l'interface 
d'administration de Wordpress.

- Si cet attribut est réglé sur true (boolean), le texte de notification utilisé sera l'intitulé de qualification de la
page d'accroche.

- Si cet attribut est réglé sur false (boolean), la fonctionnalité est alors désactivée.

- Vous pouvez personnaliser le texte de notification en affectant votre propre libellé (string).

### edit_form_notice (facultatif)

@var bool|string

Activation de l'affichage d'un message de notification dans l'interface d'administration de l'édition du post Wordpress
associé.

### listorder (déprecié)

@var string

Ordre d'affichage de la liste des choix de l'interface de réglage des pages d'accroches. 'menu_order, title' par défaut.

### object_type (déprecié)

@var string (post|taxonomy)

Type d'objet Wordpress à associé à la page d'accroche.

### object_name (déprecié)

@var string (page)

Type de post Wordpress auquel associé le type d'objet Wordpress.

### option_name (facultatif)

@var string

Nom d'enregistrement de la donnée en base.

Par défaut, utilise le nom de qualification préfixé de "page_hook_". 
ex. page_hook_my_custom_hook

> Compte tenu des restrictions de structure de la base de donnée Wordpress, veillez donc à utiliser moins de 180 
caractères. 

### rewrite (facultatif)

@var string 

Activation de la réécriture d'url native de Wordpress et modification de la requête globale de récupération des 
éléments.

Pour l'heure elle permet de déleguer l'affichage de la page les archives pour un type de post personnalisé donné.

ex : "my_custom_post@post_type"

> Lorsque cette fonctionnalité est active, les archives du type de post seront automatiquement activées et les urls 
d'accès aux posts seront réécrites.

> Il pourrait être nécessaire de recharger les régles de réécriture d'url depuis l'interface d'administration Wordpress 
**Réglages/Permaliens**.

Par défaut cette fonctionnalité est désactivée.

### route (facultatif)

@var callable

Définition du controleur de route associé à l'url du Hook.

Un argument facultatif ajouté au chemin de la route permet de gérer la pagination.

La route créée est répond uniquement à la méthode "get", pour des besoins plus spécifiques il faudra configuré une 
route manuellement.

Par défaut cette fonctionnalité est désactivée.

### show_option_none (facultatif)

@var string

Intitulé par défaut de la liste de choix des contenus d'accroche dans l'interface de réglages des pages d'accroche.

### title (recommandé)

@var string|closure

Intitulé de qualification de la page d'accroche.

## Accesseurs

### page_hook(?string $name = null)

@return tiFy\Wordpress\PageHook\PageHook|tiFy\Wordpress\PageHook\PageHookItem

Récupération l'instance du gestionnaires de pages d'accroches ou instance du controleur de page d'accroche associé au
nom de qualification passé en argument.