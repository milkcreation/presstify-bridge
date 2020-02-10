<?php

namespace tiFy\Contracts\Mail;

interface LibraryAdapter
{
    /**
     * Ajout d'une pièce jointe.
     *
     * @param string $path Chemin absolue vers le fichier.
     *
     * @return $this
     */
    public function addAttachment($filename);

    /**
     * Ajout d'un destinataire copie cachée.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addBcc($email, $name = '');

    /**
     * Ajout d'un destinataire copie carbone.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addCc($email, $name = '');

    /**
     * Ajout d'un destinataire de réponse.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addReplyTo($email, $name = '');

    /**
     * Ajout d'un destinataire.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addTo($email, $name = '');

    /**
     * Message d'erreur de traitement.
     *
     * @return string
     */
    public function error();

    /**
     * Récupération de la liste des destinataires en copie cachée.
     *
     * @return array
     */
    public function getBcc();

    /**
     * Récupération de la liste des destinataires en copie carbone.
     *
     * @return array
     */
    public function getCc();

    /**
     * Récupération de la liste des entêtes.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Récupération de la liste des destinataires en réponse.
     *
     * @return array
     */
    public function getReplyTo();

    /**
     * Récupération de la liste des destinataires.
     *
     * @return array
     */
    public function getTo();

    /**
     * Préparation de l'email en vue de l'expédition.
     *
     * @return boolean
     */
    public function prepare();

    /**
     * Expédition de l'email.
     *
     * @return boolean
     */
    public function send();

    /**
     * Définition du message au format texte.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setAlt($text);

    /**
     * Définition du message au format HTML.
     *
     * @param string $message
     *
     * @return $this
     */
    public function setBody($message);

    /**
     * Définition de l'encodage des caractères.
     *
     * @param string $charset
     *
     * @return $this
     */
    public function setCharset($charset = 'utf-8');

    /**
     * Définition du type de contenu.
     *
     * @param string $content_type multipart/alternative|text/html|text/plain
     *
     * @return $this
     */
    public function setContentType($content_type = 'multipart/alternative');

    /**
     * Définition de l'encodage du message.
     *
     * @param string $encoding 8bit|7bit|binary|base64|quoted-printable.
     *
     * @return $this
     */
    public function setEncoding($encoding);

    /**
     * Définition de l'expéditeur.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function setFrom($email, $name = '');

    /**
     * Définition du sujet.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject = '');
}