document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('image-upload');
    const fileLabel = document.querySelector('.file-upload label span:last-child');

    // Particle background
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('particles').appendChild(renderer.domElement);

    const particles = new THREE.BufferGeometry();
    const particleCount = 1000;

    const posArray = new Float32Array(particleCount * 3);
    for (let i = 0; i < particleCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 5;
    }

    particles.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

    const material = new THREE.PointsMaterial({
        size: 0.005,
        color: 0x00a8ff
    });

    const particlesMesh = new THREE.Points(particles, material);
    scene.add(particlesMesh);

    camera.position.z = 2;

    function animate() {
        requestAnimationFrame(animate);
        particlesMesh.rotation.x += 0.0005;
        particlesMesh.rotation.y += 0.0005;
        renderer.render(scene, camera);
    }
    animate();

    // Form handling
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const file = fileInput.files[0];
        if (file) {
            console.log('Uploading file:', file.name);

            // Simulate API call delay
            setTimeout(() => {
                window.location.href = 'results.php';
            }, 1000);
        }
    });

    // Update file input label
    fileInput.addEventListener('change', (e) => {
        if (fileInput.files.length > 0) {
            fileLabel.textContent = fileInput.files[0].name;
        } else {
            fileLabel.textContent = 'Choose an X-ray image';
        }
    });

    // Responsive design
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
});