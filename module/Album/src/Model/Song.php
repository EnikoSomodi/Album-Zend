<?php
namespace Album\Model;

use DomainException;
use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\File\UploadFile;
use Zend\Validator\StringLength;

/**
 * Song.
 */
class Song
{
    public $id;
    public $album_id;
    public $song_title;
    public $songpath;

    private $inputFilter;

    public function getArrayCopy()
    {
        return [
            'id'          => $this->id,
            'album_id'    => $this->album_id,
            'song_title'  => $this->song_title,
            'songpath'    => $this->songpath,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name'     => 'id',
            'required' => true,
            'filters'  => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'album_id',
            'required' => false,
            'filters'  => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'song_title',
            'required'   => true,
            'filters'    => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);

        $audiofile = new FileInput('songpath');
        $audiofile->getValidatorChain()->attach(new UploadFile());
        $audiofile->getFilterChain()->attach(
            new RenameUpload([
                'target'               => './public/song_samples/song',
                'randomize'            => true,
                'use_upload_extension' => true
            ]));
        $audiofile->setRequired(false);
        $audiofile->setAllowEmpty(true);
        $inputFilter->add($audiofile);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    public function exchangeArray(array $data)
    {
        $fields = ['id', 'album_id', 'song_title'];

        foreach ($fields as $field) {
            $this->{$field} = !empty($data[$field]) ? $data[$field] : null;
        }

        if (!empty($data['songpath'])) {
            if (is_array($data['songpath'])) {
                $this->songpath = str_replace(
                    "./public",
                    "",
                    $data['songpath']['tmp_name']
                );
            } else {
                $this->songpath = $data['songpath'];
            }
        } else {
            $data['songpath'] = null;
        }
    }
}
?>
