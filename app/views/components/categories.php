<article class='gallery'>
    <div class='categories'>
    <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
                <?php
                $categoryName = htmlspecialchars($category['name']);
                $urlCategory = urlencode($category['slug']);

                $imagePath = !empty($category['image'])
                    ? $category['image']
                    : 'placeholder.png';
                ?>
                <a href="<?= $urlCategory ?>" class='category-item-link'>
                    <div class='category-item'>
                        <img src='images/categories/<?= htmlspecialchars($imagePath) ?>'
                             alt='images/categories/<?= htmlspecialchars($imagePath) ?>'
                             title='<?= $categoryName ?>'>
                        <div class='category-name'><?= $categoryName ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>
    </div>
</article>



<style type="text/css">
.gallery {
    
    width: 60%;               
    margin: 0 auto;
    padding: 20px 0;
    display: flex;
    justify-content: center;
}

.categories {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    justify-items: center;
}

/* Contenedor de cada imagen */
.category-item {
    width: 300px;
    text-align: center;
}

/* Imagen cuadrada con “máscara” */
.category-item img {
    width: 300px;
    height: 300px;
    object-fit: cover;        
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Efecto hover */
.category-item img:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
}

/* Nombre debajo */
.category-name {
    margin-top: 10px;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    text-transform: capitalize;
    letter-spacing: 0.5px;
}

/* Responsive */
@media only screen and (max-width: 800px) {
    .gallery {
        width: 90%;
    }

    .categories {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .category-item {
        width: 100%;
    }

    .category-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
    }
}
</style>