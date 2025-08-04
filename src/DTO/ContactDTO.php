<?php
namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;
class ContactDTO{


    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le nom doit être au minimum de {{ limit }} caractères de long', maxMessage: 'Le nom doit être au maximum de {{ limit }} caractères de long')]
    public string $name ='';
    #[Assert\NotBlank(message: 'L\'email ne peut pas être vide')]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas un email valide')]
    public string $email ='';
    #[Assert\NotBlank(message: 'Le message ne peut pas être vide')]
    #[Assert\Length(min: 10, max: 1000, minMessage: 'Le message doit être au minimum de {{ limit }} caractères de long', maxMessage: 'Le message doit être au maximum de {{ limit }} caractères de long')]
    public string $message ='';
}












?>