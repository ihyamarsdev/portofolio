#!/usr/bin/env bash
set -euo pipefail

IMAGE_TAG="${1:-portofolio-php-base:8.4}"

echo "Building PHP base image: ${IMAGE_TAG}"
docker buildx build \
    -f docker/Dockerfile.base \
    -t "${IMAGE_TAG}" \
    .

echo "Successfully built and tagged ${IMAGE_TAG}"
echo "To use a custom or remote base image when building the application:"
echo "  docker buildx build --build-arg PHP_BASE_IMAGE=${IMAGE_TAG} -f docker/Dockerfile ."
