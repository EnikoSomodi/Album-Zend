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
 * Album.
 */
class Album
{
    public $id;
    public $artist;
    public $title;
    public $imagepath;
    public $number_of_songs;

    private $inputFilter;

    /**
      * Get Array Copy.
      *
      * @return array
      */
    public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
            'artist'    => $this->artist,
            'title'     => $this->title,
            'imagepath' => $this->imagepath,
            'number_of_songs' => $this->number_of_songs,
        ];
    }

    /**
      * Set Input Filter.
      *
      * @param InputFilterInterface $inputFilter
      */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
      * Get Input Filter.
      *
      * @return InputFilter
      */
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
            'name'       => 'artist',
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

        $inputFilter->add([
            'name'       => 'title',
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

        $file = new FileInput('imagepath');
        $file->getValidatorChain()->attach(new UploadFile());
        $file->getFilterChain()->attach(
            new RenameUpload([
                'target'               => './public/album_cover/image',
                'randomize'            => true,
                'use_upload_extension' => true
            ]));
        $file->setRequired(false);
        $file->setAllowEmpty(true);
        $inputFilter->add($file);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    /**
      * Exchange Array.
      *
      * @param array $data
      */
    public function exchangeArray(array $data)
    {
        $fields = ['id', 'artist', 'title', 'number_of_songs'];

        foreach ($fields as $field) {
            $this->{$field} = !empty($data[$field]) ? $data[$field] : null;
        }

        if (!empty($data['imagepath'])) {
            if (is_array($data['imagepath'])) {
                $this->imagepath = str_replace("./public", "",
                                              $data['imagepath']['tmp_name']);
            } else {
                $this->imagepath = $data['imagepath'];
            }
        } else {
            $data['imagepath'] = null;
        }
    }
}
?>
