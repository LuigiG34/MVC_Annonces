<?php

namespace App\Core;

class Form
{
    protected $formCode = '';

    /**
     * Génere le formulaire HTML
     */
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Valide si tous les champs sont remplis
     *
     * @param array $form
     * @param array $champs
     */
    public static function validation(array $form, array $champs)
    {
        foreach($champs as $champ){
            if(!isset($form[$champ]) || empty($form[$champ])){
                return false;
            }
        }
        return true;
    }

    /**
     * Ajoute les attributs envoyés à la balise
     *
     * @param array $attributs
     */
    private function ajoutAttribut(array $attributs): string
    {
        // On initialise une chaine de caracteres
        $str = '';

        // On liste les attributs "courts"
        $courts = ['checked','disabled','readonly','multiple','required','autofocus','novalidate','formnovalidate'];

        // On boucle sur le tableau d'attributs
        foreach($attributs as $attribut => $valeur){
            // Si l'attribut est dans la liste des attributs courts
            if(in_array($attribut, $courts) && $valeur == true){
                $str .= " $attribut"; 
            }else{
                // On ajoute attribut='valeur'
                $str .= " $attribut=\"$valeur\"";
            }
        }

        return $str;
    }

    /**
     * Créé la balise d'ouverture du formulaire
     *
     * @param string $method
     * @param string $action
     * @param array $attributs
     */
    public function debutForm(string $method = 'post', string $action = '#', array $attributs =[]): self
    {
        // On crée la balise form
        $this->formCode .= "<form action='$action' method='$method'";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs).'>' : '>';

        return $this;
    }

    /**
     * On ajoute la balise de fermeture du formualire
     *
     * @return self
     */
    public function finForm(): self
    {
        $this->formCode .= "</form>";

        return $this;
    }

    /**
     * Ajout d'un label
     *
     * @param string $for
     * @param string $texte
     * @param array $attributs
     */
    public function ajoutLabelFor(string $for, string $texte, array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<label for'$for'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs) : '';

        // On ajoute le texte
        $this->formCode .= ">$texte</label>";

        return $this;
    }

    /**
     * Ajout d'input
     *
     * @param string $type
     * @param string $nom
     * @param array $attributs
     */
    public function ajoutInput(string $type, string $nom, array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<input type='$type' name='$nom'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs).'>' : '>';

        return $this;
    }

    /**
     * Genere un textarea
     *
     * @param string $nom
     * @param string $valeur
     * @param array $attributs
     */
    public function ajoutTextarea(string $nom, string $valeur = '', array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<textarea name='$nom'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs) : '';

        // On ajoute le texte
        $this->formCode .= ">$valeur</textarea>";

        return $this;
    }

    /**
     * Genere un select avec ses options
     *
     * @param string $nom
     * @param array $options
     * @param array $attributs
     */
    public function ajoutSelect(string $nom, array $options, array $attributs =[]): self
    {
        // On crée le select
        $this->formCode .= "<select name='$nom'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs).'>' : '>';

        // On ajoute les options
        foreach($options as $valeur => $texte){
            $this->formCode .= "<option value=\"$valeur\">$texte</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';

        return $this;
    }

    /**
     * On génere le bouton
     *
     * @param string $texte
     * @param array $attributs
     */
    public function ajoutBouton(string $texte, array $attributs = []): self
    {
        // On ouvre le bouton
        $this->formCode .= '<button ';

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttribut($attributs) : '';

        // On ajoute le texte et on ferme
        $this->formCode .= ">$texte</button>";

        return $this;
    }
}